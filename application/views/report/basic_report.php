<!doctype html>
<head>
<style>
.card-group{
    width:45%;
    float:left;
    padding:1em;
}
.card-content{
    border: solid 1px grey;
}
.card-sbustrand{
    padding:1em;
    padding-left:2em;
}

.card-category{
    padding-left:3em;
    font-size:14px;
}
.card-score{
    float: right;
}
.title{
    font-size:16px;
    text-align:center;
    width:100%;
}
@page {
           
            footer: html_MyCustomFooter; /* display <htmlpagefooter name="MyCustomFooter"> on all pages */
        }
</style>
</head>
<body>

<htmlpagefooter name="MyCustomFooter">
        <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-style: italic;" width="100%">
            <tbody>
                <tr>
                    
                    <td style=" font-style: italic;" align="right" width="50%">{PAGENO}</td>
                    <td style="text-align: right;" width="50%">Basic Report</td>
                </tr>
            </tbody>
        </table>
    </htmlpagefooter>

<div class="title">Basic Report for <?php echo $userFullName;?> (<?php echo isset($student_level->level_name)?$student_level->level_name:''; ?>) as of <?php echo date('d-m-Y');?></div>
<?php if(isset($analysis_structure)){
    foreach($analysis_structure as $structure){
    ?>
<div class="card-group">
<div class="card-title"><?php echo $structure['name'];?></div>

<div class="card-content">
        <table>
        <?php if(isset($structure['substrand'])){
    foreach($structure['substrand'] as $substrand){
        $performance_substrand = $performance[$structure['name']][$substrand['name']];
        //array_debug($performance_substrand);exit;
        ?>
        <tr>
    <td class="card-sbustrand"><?php echo $substrand['name'];?></td><td class="card-score"><?php echo $performance_substrand['percentage'];?>%</td>
        </tr>
        <?php if(isset($substrand['category'])){
        foreach($substrand['category'] as $category){
            $performance_category = $performance_substrand[$category['name']];
        ?>
         <tr>
            <td class="card-category"><?php echo $category['name'];?></td><td class="card-score"><?php echo $performance_category['percentage'];?>%</td>
            </tr>
        <?php }
    }?>
    <?php }
}?>
    </table>
</div>
</div>
<?php }

    }?>
</body>
</html>