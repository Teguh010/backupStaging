from flask import Flask, flash, request, redirect, url_for, send_from_directory, jsonify
from werkzeug.utils import secure_filename
import json, os, requests, base64, fitz, glob
from urllib.parse import quote_plus
import cv2
import pandas as pd
import numpy as np
from pyzbar.pyzbar import decode
import time
from datetime import datetime 

# ensure that necessary directories exists
directory_list = ['C:/wamp64/www/staging/ocr/uploaded_pdf', 'C:/wamp64/www/staging/ocr/page'] 
for dir_path in directory_list:
    if not os.path.exists(dir_path):
        os.makedirs(dir_path)

MAX_FEATURES = 500
GOOD_MATCH_PERCENT = 0.15

# directory path to upload
UPLOAD_FOLDER = 'C:/wamp64/www/staging/ocr/uploaded_pdf' #'./upload/'
ALLOWED_EXTENSIONS = {'pdf'} #{'txt', 'pdf', 'png', 'jpg', 'jpeg', 'gif'}

#initialise Flask
app = Flask(__name__)

#set the upload folder for flask
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

#set the max upload filesize 10*1024*1024 = 10mb
app.config['MAX_CONTENT_LENGTH'] = 100 * 1024 * 1024

def microtime(get_as_float = False) :
    """Return current Unix timestamp with microseconds"""
    d = datetime.now()
    t = time.mktime(d.timetuple())
    if get_as_float:
        return t
    else:
        ms = d.microsecond / 1000000.
        return '%f %d' % (ms, t)

def save_student_attempt(collated_ws_id, collated_stu_id, json_payload):
    """Calls api/save_student_attempt API endpoint to save quiz attempt to database"""
    url = "https://localhost/staging/api/save_student_attempt"
    headers = {'content-type': 'application/json'}
    payload = json.dumps({'json_payload':json_payload , 'collated_stu_id': collated_stu_id, 'collated_ws_id': collated_ws_id})
    print(payload)
    return requests.request("POST", url, headers=headers, data = payload, verify=False) # need to change verfiy status!!!
    # return (response.json())

def correct_answer_api(worksheetId, userAnswer, questionType, questionNum):
    """Calls api/get_score_single_qns API endpoint to retreive score"""
    url = "https://localhost/staging/api/get_score_single_qns/{0}/{1}/{2}/{3}".format(worksheetId, quote_plus(quote_plus(userAnswer)), questionType, questionNum)
    r = requests.get(url, verify=False)
    return r.json()

def delete_temp_files():
    """Deletes all temporary PNG and PDF files created"""
    # delete PNG files
    directory='C:/wamp64/www/staging/OCR/page'
    os.chdir(directory)
    files=glob.glob('*.png')
    for filename in files:
        try:
            os.unlink(filename)
        except Exception as e:
            print('Failed to delete %s. Reason: %s' % (filename, e))       
            
    # delete JPG files
    directory='C:/wamp64/www/staging/OCR/page'
    os.chdir(directory)
    files=glob.glob('*.jpg')
    for filename in files:
        try:
            os.unlink(filename)
        except Exception as e:
            print('Failed to delete %s. Reason: %s' % (filename, e))  

    #delete PDF files
    directory='C:/wamp64/www/staging/OCR/uploaded_pdf'
    os.chdir(directory)
    files=glob.glob('*.pdf')
    for filename in files:
        try:
            os.unlink(filename)
        except Exception as e:
            print('Failed to delete %s. Reason: %s' % (filename, e))   
    
    return

def allowed_file(filename):
    """Takes in a filename string and checks if it is the correct file type."""
    return '.' in filename and \
           filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@app.errorhandler(500)
def resource_not_found(e):
    """Throws the error message if HTTP 500."""
    return jsonify(error=str(e)), 500

def call_mathpix_api(img, debug=False):
    """Sends file to Mathpix API and returns the JSON output"""
    # put desired file path here
    #file_path = 'crop_img.png'
    image_uri = "data:image/jpg;base64," + base64.b64encode(img).decode() #     image_uri = "data:image/jpg;base64," + base64.b64encode(open(file_path, "rb").read()).decode()

    r = requests.post("https://api.mathpix.com/v3/text",
        data=json.dumps({'src': image_uri}),
        headers={"app_id": "davidlowjw_gmail_com_42ddc2", "app_key": "7fab0a7045bcaf8fb949",
                 "Content-type": "application/json"})
    if debug:
        print(json.loads(r.text))
    
    #print time taken
    time_taken = r.elapsed.total_seconds()
    # print('API call returned in:', time_taken, 'seconds')
    return json.loads(r.text)#, indent=4, sort_keys=True)

def alignImages(im1, im2, imFilename = None):
    """Takes in image 1 and align it to the reference image"""

    # Load images
    im1 = cv2.imread(im1, cv2.IMREAD_COLOR)
    im2 = cv2.imread(im2, cv2.IMREAD_COLOR)

    # Convert images to grayscale
    im1Gray = cv2.cvtColor(im1, cv2.COLOR_BGR2GRAY)
    im2Gray = cv2.cvtColor(im2, cv2.COLOR_BGR2GRAY)

    # Detect ORB features and compute descriptors.
    orb = cv2.ORB_create(MAX_FEATURES)
    keypoints1, descriptors1 = orb.detectAndCompute(im1Gray, None)
    keypoints2, descriptors2 = orb.detectAndCompute(im2Gray, None)

    # Match features.
    matcher = cv2.DescriptorMatcher_create(cv2.DESCRIPTOR_MATCHER_BRUTEFORCE_HAMMING)
    matches = matcher.match(descriptors1, descriptors2, None)

    # Sort matches by score
    matches.sort(key=lambda x: x.distance, reverse=False)

    # Remove not so good matches
    numGoodMatches = int(len(matches) * GOOD_MATCH_PERCENT)
    matches = matches[:numGoodMatches]

    # Draw top matches
    # imMatches = cv2.drawMatches(im1, keypoints1, im2, keypoints2, matches, None)
    # fname = imFilename.split('/')[-1].split('.')[0]
    # cv2.imwrite(fname+"_matches.jpg", imMatches)

    # Extract location of good matches
    points1 = np.zeros((len(matches), 2), dtype=np.float32)
    points2 = np.zeros((len(matches), 2), dtype=np.float32)

    for i, match in enumerate(matches):
        points1[i, :] = keypoints1[match.queryIdx].pt
        points2[i, :] = keypoints2[match.trainIdx].pt

    # Find homography
    h, mask = cv2.findHomography(points1, points2, cv2.RANSAC)

    # Use homography
    height, width, channels = im2.shape
    im1Reg = cv2.warpPerspective(im1Gray, h, (width, height))

    return im1Reg, h

def extract_page_as_png(pdf_list):
    """Takes in filenname of all PDF and extract each page as PNG"""
    
    aligned_img = []

    for pdffile in pdf_list:

        pdffile = "./uploaded_pdf/{}".format(pdffile)
        doc = fitz.open(pdffile)
        num_pages = doc.pageCount

        # loading in each page of PDF and output as PNG
        for i in range(num_pages):
            page = doc.loadPage(i) 
            pix = page.getPixmap(alpha=False, matrix=fitz.Matrix(144/72, 144/72))
            output = "outfile_%d.png"%i
            pix.writePNG("./page/"+output)  
        
        # aligning each page to reference image
        for i in range(num_pages):
            # Read reference image
            # refFilename = "./page/outfile_ref_"+str(i)+".png"
            # imReference = cv2.imread(refFilename, cv2.IMREAD_COLOR)

            # Read image to be aligned
            inputF = './page/outfile_'+str(i)+'.png'
            # imFilename = inputF
            im = cv2.imread(inputF, cv2.IMREAD_COLOR)

            # Align image, save as imReg
            # imReg, h = alignImages(inputF, refFilename)

            # Write aligned image to disk. 
            # fname = imFilename.split('/')[-1].split('.')[0]
            outFilename = "outfile_" + str(i) + "_aligned.jpg"
            aligned_img.append(outFilename)
            outFilename = "./page/{}".format(outFilename)
            # cv2.imwrite(outFilename, imReg)
            cv2.imwrite(outFilename, im)     
    return extract_qr_and_answer(aligned_img)

def extract_qr_and_answer(aligned_img):
    """Performs OCR and auto-marking for each page"""
    stu_ans_dict = {'worksheetid':{}}
    collated_ws_id = []
    collated_stu_id = []

    # aligned_img = ['outfile_0.png','outfile_1.png','outfile_2.png','outfile_3.png']
    
    for page in aligned_img:
        # load the page
        img_path = "./page/{}".format(page)
        img_gray = cv2.imread(img_path)

        # convert page to grayscale
        # img_gray = cv2.cvtColor(img_rbg, cv2.COLOR_BGR2GRAY)

        # extracting QR data and cropping out answer box
        data = decode(img_gray)
      
        if data: # only enter for loop if there is QR code detected
            print(data)
            stu_id = None
            
            # extract stu_id from footer QR (in case this ws is created by tutor so answer QR will not have stu_id)
            for qrcode in data:
                qr_data = qrcode.data.decode('utf-8')
                if ('footer' in qr_data): 
                    stu_id = qr_data.strip().split('/')[3]

                # create a border around the QR code | can remove this block of code
                # pts = np.array([qrcode.polygon], np.int32)
                # pts = pts.reshape((-1,1,2))
                # cv2.polylines(img_gray, [pts], True, (0,0,255), 1)
            
            for qrcode in data:
                #check if this is the answer QR
                qr_data = qrcode.data.decode('utf-8')
                if not ('footer' in qr_data): 
                    # qr data (ws created by student): qns_no + '/' + student_id + '/' + tutor_id + '/' + worksheet_id;
                    # qr data (ws created by tutor): qns_no + '/' + undefined + '/' + tutor_id + '/' + worksheet_id;
                
                    # crop out the answer box
                    rect_pts = qrcode.rect
                    coordinates = img_gray[rect_pts[1]-12:rect_pts[1]+121, rect_pts[0]+145:rect_pts[0]+537]
                    buf, stu_ans = cv2.imencode('.png', coordinates.copy())

                    # ans_img_path = str(round(microtime(True) * 1000)) + '.png'
                    # cropped_ans_img = cv2.imwrite('C:/wamp64/www/staging/ocr/page/' + ans_img_path, coordinates)
                    coordinates_to_save = img_gray[rect_pts[1]-300:rect_pts[1]+100, rect_pts[0]-600:rect_pts[0]+400]

                    # perform OCR on stu_ans
                    result = call_mathpix_api(stu_ans)

                    ws_id = qr_data.strip().split('/')[-1]
                    qns_no = qr_data.strip().split('/')[-0]
                    temp_stu_id = qr_data.strip().split('/')[1]
                    
                    if temp_stu_id != 'undefined':
                        stu_id = temp_stu_id

                    if ws_id not in collated_ws_id:
                        collated_ws_id.append(ws_id)
                    
                    if stu_id not in collated_stu_id:
                        collated_stu_id.append(stu_id)

                    # Save to img folder instead and pass the filepath over to save_student_attempt
                    # cropped_ans = cv2.imwrite('./new/crop_' + str(ws_id) + '_' + str(qns_no) + '.png', coordinates_to_save)
                    ans_img_path = str(round(microtime(True) * 1000)) + str(ws_id) + str(qns_no) + str(stu_id) + '.png'
                    cropped_ans_img = cv2.imwrite('C:/wamp64/www/staging/img/studentUpload/' + ans_img_path, coordinates_to_save)

                    # add img to result array
                    result['img_path'] = ans_img_path

                    #get student's answer and remove all spaces
                    stu_ans_text = result['text'].replace(' ', '')

                    # add student's ans to mathpix result array
                    result['stu_ans'] = stu_ans_text

                    # auto-mark student's answer
                    questionType= '2' # hardcoded as open-ended qns first
                    stu_mark = correct_answer_api(str(ws_id), (str(stu_ans_text)), str(questionType), str(qns_no))

                    result['stu_mark'] = stu_mark

                    # if ws_id key does not exist   #refer to json_output_format.txt for the structure
                    if ws_id not in stu_ans_dict['worksheetid']:
                        stu_ans_dict['worksheetid'][ws_id] = { 'student_id' : { stu_id : [ { 'qns_no' : {qns_no:result} } ] } }

                    # if ws_id key exist
                    elif ws_id in stu_ans_dict['worksheetid']:

                        # if stu_id exist for this ws_id
                        if stu_id in stu_ans_dict['worksheetid'][ws_id]['student_id']:
                            stu_ans_dict['worksheetid'][ws_id]['student_id'][stu_id].append({ 'qns_no' : {qns_no:result} })
                        
                        #if stu_id does not exist for this ws_id
                        elif stu_id not in stu_ans_dict['worksheetid'][ws_id]['student_id']:
                            stu_ans_dict['worksheetid'][ws_id]['student_id'][stu_id] = [ { 'qns_no' : {qns_no:result} } ]
    
    # remove 'undefined' from collated_stu_id
    if 'undefined' in collated_stu_id:
        collated_stu_id.remove('undefined')

    print(stu_ans_dict)

    # call submitQuiz function in controller/Onlinequiz.php
    # for auto-marking and updating quiz attempt database
    save_to_db = save_student_attempt(collated_ws_id, collated_stu_id, stu_ans_dict)
    # delete all temp files
    delete_temp_files()
    return redirect("https://localhost/staging/profile")

@app.route('/ocr', methods=['POST'])
def save_uploaded_files():
    """Takes in all PDF files submitted, performs filename checking and saves all file locally"""
    pdf_list =[]

    if request.method == 'POST':

        # check if the post request has the file part
        if 'pdf_file' not in request.files:
                flash('No file part')
                return redirect(request.url)

        uploaded_files = request.files.getlist('pdf_file')

        for pdf_file in uploaded_files:
  
            # if user does not select file, browser also
            # submit an empty part without filename
            if pdf_file.filename == '':
                flash('No selected file')
                return redirect(request.url)

            if pdf_file and allowed_file(pdf_file.filename):
                # perform escaping to make sure filepath is safe
                filename = secure_filename(pdf_file.filename)

                pdf_list.append(filename)
                # save file locally
                pdf_file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))

        if len(pdf_list) > 0:
            return extract_page_as_png(pdf_list)
        else:
            return redirect(request.url)
 
    # if pdf_list != None:
    #     result = get_confidence_and_results(pdf_list)
    #     print(result)
    #     return ('<h1>Success</h1>', 201)
    # else: 
    #     abort(500, description="UPLOAD FAILED, PLEASE TRY AGAIN")

#open and display user's uploaded file (not used)
# @app.route('/uploads/<filename>')
# def uploaded_file(filename):
#     return send_from_directory(app.config['UPLOAD_FOLDER'],
#                                filename)

if __name__=='__main__':
    app.run(port=5002, debug=True)
