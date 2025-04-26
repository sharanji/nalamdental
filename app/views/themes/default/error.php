<!-- <body class="grocino-home home2">	
<div class="grocino-home home2">
    <main class="bg_gray1">
    <div id="error_page">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-xl-7 col-lg-9 margin_60 mt-5 pt-5">
                    <figure class="error-figure mt-5"><img src="<?php echo base_url()?>uploads/404.png" alt="" class="img-fluid" width="550" height="234"></figure>
                    <p class="error-message-page pb-5">We're sorry, but the page you were looking for doesn't exist.</p>
                </div>
            </div>
        </div>
    </div>	
</main>
</div>
</div> -->
<style>
.ro {
    background: #fff;
    z-index: 99999;
    display: block;
    position: fixed;
    width: 100%;
    height: 100vh;
}
.ro h1 {
    margin: 0 auto;
    text-align: center;
    position: relative;
    top: 30%;
}
.ro p {
    margin: 0 auto;
    text-align: center;
    position: relative;
    top: 30%;
    padding-top:10px;
}
p.backtohome {
    margin: 0 auto;
    margin-top: 10px!important;
    text-align: center;
    position: relative;
    top: 30%;
    padding: 10px;
    background: #ff2b2b;
    /* float: left; */
    width: 10%;
    color: #fff;
    border-radius: 4px;
}
p.backtohome:hover {
    margin: 0 auto;
    margin-top: 10px!important;
    text-align: center;
    position: relative;
    top: 30%;
    padding: 10px;
    background: #ff3f3f;
    /* float: left; */
    width: 10%;
    color: #fff;
    border-radius: 4px;
}
@media only screen and (max-width: 1200px) {
    p.backtohome {
    width: 35%;
  }
}

</style>

<div class="col-md-12">
        <div class="ro">
        <h1>404 Page Not Found</h1>
        <p>The requested page could not be found.</p>
        <a href="<?php echo base_url();?>" title="back to home"><p class="backtohome"> Back to Home</p></a>
    </div>
</div>
