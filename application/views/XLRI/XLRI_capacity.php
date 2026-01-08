<style>
.modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;
    width: 920px;
    min-width: 300px;   
}

#confirm .modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;
  	width: 420px;
    min-width: 400px;   
}
.skin-blue .main-header .navbar {
	background-color:#fff;
}
body.layout-top-nav .main-header h1 {
	color:#0699dd;
	margin-bottom:0;
	margin-top:30px;
}
.container {
	position:relative;
}
.box-header.with-border {
	background-color:#7fd1ea;
	border-top-left-radius:0;
	border-top-right-radius:0;
	margin-bottom:10px;
}
.header_blue {
	background-color:#2ea0e2 !important;
	color:#fff !important;
	margin-bottom:0 !important;
}
.box {
	border:none;
	box-shadow:none;
	border-radius:0;
	margin-bottom:0;
}
.nobg {
	background:none !important;
	border:none !important;
}
.box-title-hd {
	color:#3c8dbc;
	font-size:16px;
	margin:0;
}
.blue_bg {
	background-color:#e7f3ff;
}
.m_t_15 {
	margin-top:15px;
}
.main-footer {
	padding-left:160px;
	padding-right:160px;
}
.content-header > h1 {
	font-size:22px;
	font-weight:600;
}
h4 {
	margin-top:5px;
	margin-bottom:10px !important;
	font-size:14px;
	line-height:18px;
	padding:0 5px;
	font-weight:600;
	text-align:justify;
}
.form-horizontal .control-label {
	padding-top:4px;
}
.pad_top_2 {
	padding-top:2px !important;
}
.pad_top_0 {
	padding-top:0px !important;
}

div.form-group:nth-child(odd) {
	background-color:#dcf1fc;
	padding:5px 0;
}

#confirmBox
{
    display: none;
    background-color: #eee;
    border-radius: 5px;
    border: 1px solid #aaa;
    position: fixed;
    width: 300px;
    left: 50%;
    margin-left: -150px;
    padding: 6px 8px 8px;
    box-sizing: border-box;
    text-align: center;
	z-index:1;
	box-shadow:0 1px 3px #000;
}
#confirmBox .button {
    background-color: #ccc;
    display: inline-block;
    border-radius: 3px;
    border: 1px solid #aaa;
    padding: 2px;
    text-align: center;
    width: 80px;
    cursor: pointer;
}
#confirmBox .button:hover
{
    background-color: #ddd;
}
#confirmBox .message
{
    text-align: left;
    margin-bottom: 8px;
}
.form-group {
	margin-bottom:10px;
}
.form-horizontal .form-group {
	margin-left:0;
	margin-right:0;

}
.form-control {
	border-color:#888;
}
.form-horizontal .control-label {
	font-weight:normal;
}
a.forget  {color:#9d0000;}
a.forget:hover {color:#9d0000; text-decoration:underline;}
ol li {
	line-height:18px;
}
.example {
	text-align:left !important;
	padding:0 10px;
}
</style>

<?php //echo '<pre>';print_r($user_info_details[0]);die;?>
	<div class="container">
		<!--<section class="content-header">
			<h1 class="register">Thank you</h1><br/>
		</section>-->
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					
					<!-- Thank you -->
					<div class="box box-info">
						<div class="box-header with-border" align="center">
							<h3 class="box-title">XLRI 2021-22 Application seats are full.</h3><br>
							</div>
                        
                        
                      		 
                      
					</div>
					<!-- Thank you box close -->
					
				</div>
			</div>
		</section>
	</div>
	
	<script>
(function (global) {

	if(typeof (global) === "undefined")
	{
		throw new Error("window is undefined");
	}

    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";

		// making sure we have the fruit available for juice....
		// 50 milliseconds for just once do not cost much (^__^)
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };
	
	// Earlier we had setInerval here....
    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };

    global.onload = function () {
        
		noBackPlease();

		// disables backspace on page except on input fields and textarea..
		document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
        };
		
    };

})(window);
</script>