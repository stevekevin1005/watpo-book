<!Doctype html>
<html lang="zh">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>泰和殿-客戶問卷</title>
	<link rel='stylesheet prefetch' href='https://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css'>
	<link rel='stylesheet prefetch' href='/assets/css/animate.min.css'>
	<link rel="stylesheet" type="text/css" href="/assets/css/report.css">
	<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,100,400,300,600,700,800'>
	<link rel="stylesheet" type="text/css" href="/assets/css/report-styles.css">
    <style type="text/css">
    .form-group label{
        font-size: 17px;
        font-weight: bold;
    }
    </style>
</head>
<body>
	<div class="covering">
  <div class="progress">
    <div class="progress-bar" style="width: 0;"> <span class="sr-only">0% Complete</span> </div>
  </div>
    <div class="htmleaf-header">
    	<h1 class="h1-header animated bounceInLeft">客戶意見表</h1>
    </div>
  <div class="container-fluid table-class">
    <div class="black " >
      <div class="inner cover">
        <div class="centerUp">
          <div class="questionaire">
            <form>
              <ul class="progress-form">
                <li class="form-group animated q0 fadeInRightBig activate" data-color="#E1523D" data-percentage="20%">
                    <label for="recep_satis">
                        <h3>您對這次消費時的櫃檯人員</h3>
                    </label>
                    <div class="recep_satis">
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="0_1" name="receptionist" value="早班櫃檯"/><label for='0_1' >早班櫃檯</label> 
                            </div> 
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="0_2" name="receptionist" value="晚班櫃檯"/><label for='0_2'>晚班櫃檯</label>
                            </div> 
                        </div>               
                </li>
                <li class="form-group animated q1 hide inactive" data-color="#E1523D" data-percentage="20%">
                    <label for="recep_satis">
                        <h3>您對這次櫃檯人員的服務態度</h3>
                    </label>
                    <div class="recep_satis">
                        
                    
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="1_1" name="receptionist_satisfaction" value="1-非常滿意"/><label for="1_1">非常滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="1_2" name="receptionist_satisfaction" value="1-滿意"/><label for="1_2">滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="1_3" name="receptionist_satisfaction" value="1-普通"/><label for="1_3">普通</label> 
                            </div>
                            <div class="element">
                                <input class="selector" type="radio" id="1_4" name="receptionist_satisfaction" value="1-不滿意"/><label for="1_4">不滿意</label> 
                                
                            </div>
                        </div>
                    <textarea onkeyup="adjustHeight(this)" name="reason" class="form-control getName q1-reason" style="display:none" id="1_5" placeholder="原因"  required="required"></textarea>
                    <span class="alert_reason hidden">請輸入原因，進行下一題</span>
                </li>
                <li class="form-group animated q2 hide " data-color="#7C6992"  data-percentage="40%">
                    <label for="service_providers_attitude">
                        <h3>您對這次按摩師傅 {{$service_provider_information}} 服務態度</h3>
                    </label>
                    <div class="service_providers_attitude">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="2_1" name="service_providers_attitude" value="2-非常滿意"/><label for="2_1">非常滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="2_2" name="service_providers_attitude" value="2-滿意"/><label for="2_2">滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="2_3" name="service_providers_attitude" value="2-普通"/><label for="2_3">普通</label> 
                            </div>
                            <div class="element">
                                <input class="selector" type="radio" id="2_4" name="service_providers_attitude" value="2-不滿意"/><label for="2_4">不滿意</label> 
                                
                            </div>
                        </div>
                    <textarea name="reason" class="form-control getName q2-reason" style="display:none" id="2_5" placeholder="原因"  required="required"></textarea>
                    <span class="alert_reason hidden">請輸入原因，進行下一題</span>
                </li>
                <li class="form-group animated q3 hide" data-color="#00AF66"  data-percentage="60%">
                    <label for="service_providers_skill">
                        <h3>您對這次按摩師傅 {{$service_provider_information}} 的技術</h3>
                    </label>
                    <div class="service_providers_skill">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="3_1" name="service_providers_skill" value="3-非常滿意"/><label for="3_1">非常滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="3_2" name="service_providers_skill" value="3-滿意"/><label for="3_2">滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="3_3" name="service_providers_skill" value="3-普通"/><label for="3_3">普通</label> 
                            </div>
                            <div class="element">
                                <input class="selector" type="radio" id="3_4" name="service_providers_skill" value="3-不滿意"/><label for="3_4">不滿意</label> 
                                
                            </div>
                        </div>
                    <textarea name="reason" class="form-control getName q3-reason" style="display:none" id="3_5" placeholder="原因"  required="required"></textarea>  
                    <span class="alert_reason hidden">請輸入原因，進行下一題</span>
                </li>
                <li class="form-group animated q4 hide" data-color="#00AF66"  data-percentage="80%">
                    <label for="service_providers_work">
                        <h3>您對這次按摩師傅 {{$service_provider_information}} 的工作表現</h3>
                    </label>
                    <div class="service_providers_work">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="4_1" name="service_providers_work" value="4-非常滿意"/><label for="4_1">非常滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="4_2" name="service_providers_work" value="4-滿意"/><label for="4_2">滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="4_3" name="service_providers_work" value="4-普通"/><label for="4_3">普通</label> 
                            </div>
                            <div class="element">
                                <input class="selector " type="radio" id="4_4" name="service_providers_work" value="4-不滿意"/><label for="4_4">不滿意</label> 
                            </div>
                        </div>
                    <textarea name="reason" class="form-control getName q4-reason" style="display:none" id="4_5" placeholder="原因"  required="required"></textarea>  
                    <span class="alert_reason hidden">請輸入原因，進行下一題</span>
                </li>
                <li class="form-group animated q5 hide" data-color="#00AF66"  data-percentage="100%">
                    <label for="service_providers_forbidden">
                        <h3>本次按摩師{{$service_provider_information}}工作是否有以下情況，請勾選</h3>
                    </label>
                    <div class="service_providers_forbidden">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="checkbox" id="5_1" name="service_providers_forbidden" value="打瞌睡"/><label for="5_1">打瞌睡</label> 
                            </div>
                            <div class="element next">
                                <input class="selector satisfy" type="checkbox" id="5_2" name="service_providers_forbidden" value="師傅相互聊天影響休息"/><label for="5_2">師傅相互聊天影響休息</label> 
                            </div>
                        </div>
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="checkbox" id="5_3" name="service_providers_forbidden" value="看電視"/><label for="5_3">看電視</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="checkbox" id="5_4" name="service_providers_forbidden" value="用手機"/><label for="5_4">用手機</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="checkbox" id="5_5" name="service_providers_forbidden" value="無"/><label for="5_5">無</label> 
                            </div>
                        </div>
                    <textarea name="service_providers_forbidden" class="form-control getName service_providers_forbidden"  id="5_6" placeholder="其他"  required="required"></textarea>  
                </li>
                <li class="form-group animated q6 hide" data-color="#00AF66"  data-percentage="80%">
                    <label for="come_again">
                        <h3>下次還會不會來本店消費</h3>
                    </label>
                    <div class="come_again">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="6_1" name="come_again" value="6-會"/><label for="6_1">會</label> 
                            </div>
                            <div class="element">
                                <input class=" selector" type="radio" id="6_2" name="come_again" value="6-不會"/><label for="6_2">不會</label> 
                            </div>
                        </div>
                    </div>
                    <textarea name="reason" class="form-control getName q6-reason" style="display:none" id="6_3" placeholder="原因"  required="required"></textarea>  
                    <span class="alert_reason hidden">請輸入原因，進行下一題</span>
                </li>
                <li class="form-group animated q7 hide" data-color="#00AF66"  data-percentage="80%">
                    <label for="suggestion">
                        <h3>您對本公司的建議</h3>
                    </label>
                    
                    <input type="text" name="suggestion" class="form-control getName" id="7_1" placeholder=""  required="required"/>  
                </li>
                <li class="form-group animated hide " data-color="#00AF66"  data-percentage="100%">
                  <h3>確認您的回覆:</h3>
                  <div class=" editable scrollable">
                    <span>您對這次消費時的櫃檯人員:</span>
                    <div class="break answer0" contenteditable="false"></div>
                    <span>您對這次櫃檯人員的服務態度:</span>
                    <div class="break answer1" contenteditable="false"></div>
                    <span>您對這次按摩師傅 {{$service_provider_information}} 服務態度:</span>
                    <div class="break answer2" contenteditable="false"></div>
                    <span>您對這次按摩師傅 {{$service_provider_information}} 的技術:</span>
                    <div class="break answer3" contenteditable="false"></div>
                    <span>您對這次按摩師傅 {{$service_provider_information}} 的工作表現:</span>
                    <div class="break answer4" contenteditable="false"></div>
                    <span>本次按摩師 {{$service_provider_information}} 工作是否有以下情況:</span>
                    <div class="break answer5" contenteditable="false"></div>
                    <span>下次還會不會來本店消費:</span>
                    <div class="break answer6" contenteditable="false"></div>
                    <span>您對本公司的建議:</span>
                    <div class="break answer7" contenteditable="false"></div>
                    
                  </div>
                </li>
              </ul>
            </form>
          </div>
          <div class="count"><span class="img_cnt"></span> / <span class="img_amt"></span>    本意見函回覆不會產生任何費用</div>
          <div class="btn-union form">
            <button class="btn btn-default btn-lg pre animated fadeOutDown" >上一步 <span class="icon-next"></span></button>
            <button class="btn btn-default btn-lg nxt animated fadeOutDown" >下一步 <span class="icon-next"></span></button>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    <form id="hiddenForm" method="post" action='/api/report'>
      <input hidden="" class="ans0" name="q0" style="color:#444"/>   
      <input hidden="" class="ans1" name="q1" style="color:#444"/>
      <input hidden="" class="ans-reason1" name="q1_reason" style="color:#444"/>
      <input hidden="" class="ans2" name="q2" style="color:#444"/>
      <input hidden="" class="ans-reason2" name="q2_reason" style="color:#444"/>
      <input hidden="" class="ans3" name="q3" style="color:#444"/>
      <input hidden="" class="ans-reason3" name="q3_reason" style="color:#444"/>
      <input hidden="" class="ans4" name="q4" style="color:#444"/>
      <input hidden="" class="ans-reason4" name="q4_reason" style="color:#444"/>
      <input hidden="" class="ans5" name="q5" style="color:#444"/>
      <input hidden="" class="ans6" name="q6" style="color:#444"/>
      <input hidden="" class="ans-reason6" name="q6_reason" style="color:#444"/>
      <input hidden="" class="ans7" name="q7" style="color:#444"/>
      <input hidden="" class="jwt" name="jwt"/>
      <div class="btn-union hiddenForm">
        <input type="button" class="btn btn-default btn-lg pre animated hide" value="上一步"> <span class="icon-next"></span></input>
        <input type="submit" class="btn btn-default btn-lg animated hide submit" value="送出"/>
      </div>
    </form>
  </div>
</div>
</div>
<div class="animated thanks hide">
  <h1 style="font-weight:100; font-size:48px; width: 80%; margin: 0 auto;">感謝您耐心填寫</h1>
  <form action="/">
    <button class="btn btn-lg btn-success" style="margin:20px;" >回首頁</button>
  </form>
</div>
	<script src="/assets/js/jquery-2.1.1.min.js"></script>
	<script src='https://cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
	<script>
    function adjustHeight(o){
        o.style.height = "1px";
        o.style.height = (20+o.scrollHeight)+"px";
    }
    var multi_Ans=Array(8)
    for(var i =0; i <8 ;i++){
        multi_Ans[i]=[];
    }
    

    $.urlParam = function(name){
	    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }
    function en2ch(text){
        if(text.search(',')>0){
            var ans=[];
            for(var i = 0; i < text.split(',').length ;i++){
                switch(text.split(',')[i]){
                    case 'nap':
                        ans.push("打瞌睡");
                        break;
                    case 'chatting':
                        ans.push("與其他師傅聊天");
                        break;
                    case 'tv':
                        ans.push("看電視");
                        break;
                    case 'phone':
                        ans.push("用手機");
                        break;
                    case 'no':
                        ans.push("無");
                        break;
                    default:
                        ans.push(text.split(',')[i]);
                        break;
                }
            }

            return ans.join(',');
        }
        else{
            var ans=text;
            switch(text){
                case 'morning':
                    ans='早班櫃檯';
                    break;
                case 'night':
                    ans="晚班櫃檯";
                    break;
            }
            return ans;
        }
    }
    function score2text(score){
        var text='';
        switch(score){
            case '3':
                text="非常滿意";
                break;
            case '2':
                text="滿意";
                break;
            case '1':
                text="普通";
                break;
            case '0':
                text="不滿意";
                break;
            case 'y':
                text="會";
                break;
            case 'n':
                text="不會";
                break;
        }
        return text;
    }
    $('.selector').click(function() {
        var question_number=$(this).attr('id').split('_')[0];
        var question_val = $(this).val();
        console.log(question_val);

        $(".q"+question_number+"-reason").show();
        $('.answer'+question_number).html(question_val.split("-")[1]?question_val.split("-")[1]:en2ch(question_val))
        $('.ans'+question_number).val(question_val.split("-")[1]?question_val.split("-")[1]:question_val.toString())
        if(question_val.split("-")[1] == "不滿意" || question_val.split("-")[1]== "不會"){
            if($(".q"+question_number+"-reason").val()==""){
                $('.alert_reason').removeClass('hidden').addClass('fadeInUp');
                $('.nxt').removeClass('fadeInUp').addClass('fadeOutDown');
            }
            
        }
        else{
            $('.alert_reason').addClass('hidden').removeClass('fadeInUp');
            // console.log("#q"+question_number+"-reason hide")
            // $(".q"+question_number+"-reason").hide();
            // $('.answer'+question_number).html(question_val.split("-")[1]?question_val.split("-")[1]:en2ch(question_val))
            // $('.ans'+question_number).val(question_val.split("-")[1]?question_val.split("-")[1]:question_val.toString())
        }
    }); 
	$(function () {
        $('.jwt').html($.urlParam('jwt'));
        $('.jwt').val($.urlParam('jwt'));
	    $('textarea.getName').keyup('keyup', function () {
	        $('.cName').html($('.getName').val());
	    });
	    $('.help').popover();
	    $('.submit').click(function (event) {
            event.preventDefault();

            $.ajax({
                url: '/api/report',
                type: 'post',
                dataType: 'json',
                data: $('form#hiddenForm').serialize(),
                success: function(data) {
                    console.log(data);
                    if(data.res==1){
                        var darken = '<div class="darken" style="display:none;"></div>';
                        $('body').prepend(darken);
                        $('.darken').delay().show(0).animate({ opacity: 0.8 }, 'fast');
                        $('.thanks').removeClass('hide').addClass('fadeInDownBig');
                    }
                    else{

                    }
                    
                }
            });

	        
            
	    });
	    var img_cnt = $('li.activate').index() + 1;
	    var img_amt = $('li.form-group').length;
	    $('.img_cnt').html(img_cnt);
	    $('.img_amt').html(img_amt);
	    var progress = $('.img_cnt').text() / $('.img_amt').text() * 100;
	    $('.progress-bar').css('width', progress + '%');
	    $('.satisfy').click(function () {
            $('.nxt').removeClass('hide fadeOutDown').addClass('fadeInUp');
        });
        $('textarea[name=reason]').keyup(function () {
            if($(this).val()!== ""){
                $('.alert_reason').addClass('hidden').removeClass('fadeInUp');
                $('.nxt').removeClass('hide fadeOutDown').addClass('fadeInUp');
            }
            else{
                var question_no = $(this).attr('id').split('_')[0];             
                if($('.answer'+question_no).html().indexOf("不滿意") >=0 || $('.answer'+question_no).html().indexOf("不會") >= 0){
                    $('.nxt').removeClass('fadeInUp').addClass('fadeOutDown');
                    $('.alert_reason').removeClass('hidden').addClass('fadeInUp');
                }
            }
        });

        
	    $('.nxt').click(function () {
            $('.alert_reason').addClass('hidden').removeClass('fadeInUp');
            var next_q_number = $('li.activate').index()+1;
            console.log("val:"+$('.ans'+next_q_number).val())
            if( $('.ans'+next_q_number).val()=="" && next_q_number!==7)
	            $('.nxt').removeClass('fadeInUp').addClass('fadeOutDown');
            if( next_q_number == 1){
                $('button.pre').removeClass('fadeOutDown').addClass('fadeInUp');
            }
	        if ($('.progress-form li').hasClass('activate')) {
	            $('p.alerted').removeClass('fadeInLeft').addClass('fadeOutUp');
                var $activate = $('li.activate');
	            var $inactive = $('li.inactive');
                // $preactive.removeClass('preactive');
	            $activate.removeClass('fadeInRightBig activate').addClass('fadeOutLeftBig');
	            $inactive.removeClass('hide inactive').addClass('activate fadeInRightBig').next().addClass('inactive');
	            var img_cnt = $('li.activate').index() + 1;
	            var img_amt = $('li.form-group').length;
	            $('.img_cnt').html(img_cnt);
	            $('.img_amt').html(img_amt);
	            var progress = $('.img_cnt').text() / $('.img_amt').text() * 100;
	            $('.progress-bar').css('width', progress + '%');
	            if ($('.img_cnt').html() == $('.img_amt').html()) {
	                $('.count, .nxt, button.pre').hide();
	                $('.submit').removeClass('hide');
                    $('input.pre').removeClass('hide');
	            }
	        }
	    });
        $('.pre').click(function () {
            $('.alert_reason').addClass('hidden').removeClass('fadeInUp');
            $('.nxt').addClass('fadeInUp').removeClass('fadeOutDown');
	        var img_cnt = $('li.activate').index();
            var img_amt = $('li.form-group').length;
            $('.img_cnt').html(img_cnt);
            $('.img_amt').html(img_amt);
            var progress = $('.img_cnt').text() / $('.img_amt').text() * 100;
            $('.progress-bar').css('width', progress + '%');
            if ($('.img_cnt').html() == $('.img_amt').html()-1) {
                $('.count, .nxt').show();
                $('input.pre').addClass('hide');
                $('.submit').addClass('hide');
            }
            if(img_cnt == 1)
                $('button.pre').removeClass('fadeInUp').addClass('fadeOutDown');
            if(img_cnt == img_amt-1)
                $('button.pre').show();
            var $preactivate = $('li.q'+(img_cnt-1));
            var $activate = $('li.activate');
            var $inactive = $('li.inactive');
            $preactivate.addClass('fadeInRightBig activate').removeClass('fadeOutLeftBig');
            $activate.addClass('hide inactive').removeClass('fadeInRightBig activate');
            $inactive.removeClass('inactive');

	    });
    });

	$(function () {
	    $('textarea[name=reason]').keyup(function () {
            var Value = $(this).val();
            var q_number = $(this).attr("id").split("_")[0];
            
            $('.answer'+q_number).html($('.ans'+q_number).val()+",原因："+Value);
            $('.ans-reason'+q_number).val(Value.toString());
        });

        
        
        $('textarea[name=service_providers_forbidden]').click(function(){
            
            var q_number = $(this).attr("id").split("_")[0];
            multi_Ans[q_number]=[]
            $('input[name=service_providers_forbidden]:checked').each(function() {
                multi_Ans[q_number].push($(this).val());
            });
            multi_Ans[q_number].push($('.service_providers_forbidden').val())
            console.log(q_number+":"+$('.service_providers_forbidden').val());
            $('.answer'+q_number).html(en2ch(multi_Ans[q_number].join(" ")));
            $('.ans'+q_number).val(multi_Ans[q_number].join(" "));
        });
        $('textarea.service_providers_forbidden').keyup(function(){
            
            var q_number = $(this).attr("id").split("_")[0];
            multi_Ans[q_number]=[]
            $('input[name=service_providers_forbidden]:checked').each(function() {
                multi_Ans[q_number].push($(this).val());
            });
            multi_Ans[q_number].push($(this).val())
            console.log(q_number+"::"+$(this).val());
            $('.answer'+q_number).html(en2ch(multi_Ans[q_number].join(",")));
            $('.ans'+q_number).val(multi_Ans[q_number].join(","));
        });
        
	    
        $('textarea[name=suggestion]').keyup(function () {
	        var Value = $(this).val();
            $('.answer7').html(Value);
            $('.ans7').val(Value.toString());
	    });
	});
	</script>
</body>
</html>