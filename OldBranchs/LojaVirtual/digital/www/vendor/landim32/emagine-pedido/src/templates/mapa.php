<style type="text/css">
    #mapa {
        height: 500px;
        width: 100%;
    }
    .label_content{
        position:relative;
        border-radius: 5px;
        padding:5px;
        color:#ffffff;
        background-color: red;
        font-size: 20px;
        width: 100%;
        line-height: 20px;
        text-align: center;
    }
    .label_content:after {
        content:'';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -10px;
        width: 0;
        height: 0;
        border-top: solid 10px red;
        border-left: solid 10px transparent;
        border-right: solid 10px transparent;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="mapa"></div>
        </div>
    </div>
</div>