
function checkFileExt(file) {
    let allow_type = ['jpg', 'jpeg', 'png'];
    let ext = file.name.substring(file.name.lastIndexOf('.') + 1).toLowerCase();
    let re = false;
    for (let i = 0; i < allow_type.length; i++) {
        if (ext == allow_type[i].toLocaleLowerCase()) { re = true; break; }
    }
    return re;
}
function _previewImage(file, size) {
    let imgSrc;
    return new Promise(function (resolve, reject) {
        //to be modified
        let fr = new FileReader();
        fr.onload = function (e) {

            imgSrc = window.URL.createObjectURL(new Blob([e.target.result]));

            let img = new Image();
            img.src = imgSrc;
            img.onload = function () {
                resolve(_resizeBgPicture(img, size));
            }


        };
        fr.readAsArrayBuffer(file);
    });
}

function _resizeBgPicture(image, size) {
    let oWidth = image.naturalWidth;
    let oHeight = image.naturalHeight;
    let oSize = { oHeight: oHeight, oWidth: oWidth };
    let newSize = { height: size, width: size * oWidth / oHeight };

    let newImage = renderPollImage(image, newSize, oSize);
    return newImage;
}
function _resizePicture(image, newHeight) {
    let oWidth = image.naturalWidth;
    let oHeight = image.naturalHeight;
    //measure ratio
    let newWidth;
    newWidth = oWidth * newHeight / oHeight;

    //let oHeight = oWidth * 3 / 4;
    let newImage, newSize;
    let oSize = { oHeight: oHeight, oWidth: oWidth };
    //if (oHeight > oWidth) {
    newSize = { height: newHeight, width: newWidth };
    //} else {
    //    newSize = { height: 500, width: 500 * oWidth / oHeight };
    //}
    newImage = renderPollImage(image, newSize, oSize);
    return newImage;

}
function renderPollImage(oImg, newSize, oSize) {
    //need screen adjust for ios
    let width_source = oSize.oWidth;
    let height_source = oSize.oHeight;

    let canvas = document.createElement('canvas');
    let ctx = canvas.getContext("2d");
    let canvas2 = document.createElement('canvas');
    let ctx2 = canvas2.getContext("2d");

    canvas.width = width_source;
    canvas.height = height_source;
    canvas2.width = newSize.width;
    canvas2.height = newSize.height;

    // draw image
    ctx.drawImage(oImg, 0, 0);

    let width = Math.round(canvas2.width);
    let height = Math.round(canvas2.height);
    width_source = Math.round(canvas.width);
    height_source = Math.round(canvas.height);

    let ratio_w = width_source / width;
    let ratio_h = height_source / height;
    let ratio_w_half = Math.ceil(ratio_w / 2);
    let ratio_h_half = Math.ceil(ratio_h / 2);

    let img = ctx.getImageData(0, 0, width_source, height_source);
    let img2 = ctx.createImageData(width, height);
    let data = img.data;
    let data2 = img2.data;

    for (let j = 0; j < height; j++) {
        for (let i = 0; i < width; i++) {
            let x2 = (i + j * width) * 4;
            let weight = 0;
            let weights = 0;
            let weights_alpha = 0;
            let gx_r = 0;
            let gx_g = 0;
            let gx_b = 0;
            let gx_a = 0;
            let center_y = (j + 0.5) * ratio_h;
            let yy_start = Math.floor(j * ratio_h);
            let yy_stop = Math.ceil((j + 1) * ratio_h);
            for (let yy = yy_start; yy < yy_stop; yy++) {
                let dy = Math.abs(center_y - (yy + 0.5)) / ratio_h_half;
                let center_x = (i + 0.5) * ratio_w;
                let w0 = dy * dy; //pre-calc part of w
                let xx_start = Math.floor(i * ratio_w);
                let xx_stop = Math.ceil((i + 1) * ratio_w);
                for (let xx = xx_start; xx < xx_stop; xx++) {
                    let dx = Math.abs(center_x - (xx + 0.5)) / ratio_w_half;
                    let w = Math.sqrt(w0 + dx * dx);
                    if (w >= 1) {
                        //pixel too far
                        continue;
                    }
                    //hermite filter
                    weight = 2 * w * w * w - 3 * w * w + 1;
                    let pos_x = 4 * (xx + yy * width_source);
                    //alpha
                    gx_a += weight * data[pos_x + 3];
                    weights_alpha += weight;
                    //colors
                    if (data[pos_x + 3] < 255)
                        weight = weight * data[pos_x + 3] / 250;
                    gx_r += weight * data[pos_x];
                    gx_g += weight * data[pos_x + 1];
                    gx_b += weight * data[pos_x + 2];
                    weights += weight;
                }
            }
            data2[x2] = gx_r / weights;
            data2[x2 + 1] = gx_g / weights;
            data2[x2 + 2] = gx_b / weights;
            data2[x2 + 3] = gx_a / weights_alpha;
        }
    }

    ctx2.putImageData(img2, 0, 0);

    return canvas2.toDataURL('image/jpeg', 0.9);
}
function dataURItoBlob(dataURI) {
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0)
        byteString = window.atob(dataURI.split(',')[1]);
    else
        byteString = unescape(dataURI.split(',')[1]);

    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

    // write the bytes of the string to a typed array
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ia], { type: mimeString });
}
