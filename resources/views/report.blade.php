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
                <li class="form-group animated fadeInRightBig  activate" data-color="#E1523D" data-percentage="20%">
                    <label for="recep_satis">
                        <h3>您對這次消費時的櫃檯人員</h3>
                    </label>
                    <div class="recep_satis">
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="0" name="receptionist" value="morning"/><label>早班櫃檯</label> 
                            </div> 
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="0" name="receptionist" value="night"/><label>晚班櫃檯</label>
                            </div> 
                        </div>               
                </li>
                <li class="form-group animated hide inactive" data-color="#E1523D" data-percentage="20%">
                    <label for="recep_satis">
                        <h3>您對這次櫃檯人員的服務態度</h3>
                    </label>
                    <div class="recep_satis">
                        
                    
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="1" name="receptionist_satisfaction" value="1-3"/><label>非常滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="1" name="receptionist_satisfaction" value="1-2"/><label>滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="1" name="receptionist_satisfaction" value="1-1"/><label>普通</label> 
                            </div>
                            <div class="element">
                                <input class="selector" type="radio" id="1" name="receptionist_satisfaction" value="1-0"/><label>不滿意</label> 
                            </div>
                        </div>
                    <input type="text" name="reason" class="form-control getName q1-reason" style="display:none" id="1" placeholder="不滿原因"  required="required"/>
                </li>
                <li class="form-group animated hide " data-color="#7C6992"  data-percentage="40%">
                    <label for="service_providers_attitude">
                        <h3>您對這次按摩師傅服務態度</h3>
                    </label>
                    <div class="service_providers_attitude">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="2" name="service_providers_attitude" value="2-3"/><label>非常滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="2" name="service_providers_attitude" value="2-2"/><label>滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="2" name="service_providers_attitude" value="2-1"/><label>普通</label> 
                            </div>
                            <div class="element">
                                <input class="selector" type="radio" id="2" name="service_providers_attitude" value="2-0"/><label>不滿意</label> 
                            </div>
                        </div>
                    <input type="text" name="reason" class="form-control getName q2-reason" style="display:none" id="2" placeholder="不滿原因"  required="required"/>
                </li>
                <li class="form-group animated hide" data-color="#00AF66"  data-percentage="60%">
                    <label for="service_providers_skill">
                        <h3>您對這次按摩師傅的技術</h3>
                    </label>
                    <div class="service_providers_skill">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="3" name="service_providers_skill" value="3-3"/><label>非常滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="3" name="service_providers_skill" value="3-2"/><label>滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="3" name="service_providers_skill" value="3-1"/><label>普通</label> 
                            </div>
                            <div class="element">
                                <input class="selector" type="radio" id="3" name="service_providers_skill" value="3-0"/><label>不滿意</label> 
                            </div>
                        </div>
                    <input type="text" name="reason" class="form-control getName q3-reason" style="display:none" id="3" placeholder="不滿原因"  required="required"/>  
                </li>
                <li class="form-group animated hide" data-color="#00AF66"  data-percentage="80%">
                    <label for="service_providers_work">
                        <h3>您對這次按摩師傅的工作表現</h3>
                    </label>
                    <div class="service_providers_work">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="4" name="service_providers_work" value="4-3"/><label>非常滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="4" name="service_providers_work" value="4-2"/><label>滿意</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="4" name="service_providers_work" value="4-1"/><label>普通</label> 
                            </div>
                            <div class="element">
                                <input class="selector " type="radio" id="4" name="service_providers_work" value="4-0"/><label>不滿意</label> 
                            </div>
                        </div>
                    <input type="text" name="reason" class="form-control getName q4-reason" style="display:none" id="4" placeholder="不滿原因"  required="required"/>  
                </li>
                <li class="form-group animated hide" data-color="#00AF66"  data-percentage="100%">
                    <label for="service_providers_forbidden">
                        <h3>本次按摩師工作是否有以下情況，請勾選</h3>
                    </label>
                    <div class="service_providers_forbidden">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="checkbox" id="5" name="service_providers_forbidden" value="nap"/><label>打瞌睡</label> 
                            </div>
                            <div class="element next">
                                <input class="selector satisfy" type="checkbox" id="5" name="service_providers_forbidden" value="chatting"/><label>師傅相互聊天影響休息</label> 
                            </div>
                        </div>
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="checkbox" id="5" name="service_providers_forbidden" value="tv"/><label>看電視</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="checkbox" id="5" name="service_providers_forbidden" value="phone"/><label>用手機</label> 
                            </div>
                            <div class="element">
                                <input class="selector satisfy" type="checkbox" id="5" name="service_providers_forbidden" value="no"/><label>無</label> 
                            </div>
                        </div>
                    <input type="text" name="service_providers_forbidden" class="form-control getName service_providers_forbidden"  id="5" placeholder="其他"  required="required"/>  
                </li>
                <li class="form-group animated hide" data-color="#00AF66"  data-percentage="80%">
                    <label for="come_again">
                        <h3>下次還會不會來本店消費</h3>
                    </label>
                    <div class="come_again">                  
                        <div class="each_line">
                            <div class="element">
                                <input class="selector satisfy" type="radio" id="6" name="come_again" value="6-1"/><label>會</label> 
                            </div>
                            <div class="element">
                                <input class=" selector" type="radio" id="6" name="come_again" value="6-0"/><label>不會</label> 
                            </div>
                        </div>
                    </div>
                    <input type="text" name="reason" class="form-control getName q6-reason" style="display:none" id="6" placeholder="不會原因"  required="required"/>  
                </li>
                <li class="form-group animated hide" data-color="#00AF66"  data-percentage="80%">
                    <label for="suggestion">
                        <h3>您對本公司的建議</h3>
                    </label>
                    
                    <input type="text" name="suggestion" class="form-control getName" id="7" placeholder="若無，則填寫無"  required="required"/>  
                </li>
                <li class="form-group animated hide " data-color="#00AF66"  data-percentage="100%">
                  <h3>確認您的回覆:</h3>
                  <div class=" editable scrollable">
                    <div class="break answer0" contenteditable="false"></div>
                    <div class="break answer1" contenteditable="false"></div>
                    <div class="break answer2" contenteditable="false"></div>
                    <div class="break answer3" contenteditable="false"></div>
                    <div class="break answer4" contenteditable="false"></div>
                    <div class="break answer5" contenteditable="false"></div>
                    <div class="break answer6" contenteditable="false"></div>
                    <div class="break answer7" contenteditable="false"></div>
                    
                  </div>
                </li>
              </ul>
            </form>
          </div>
          <div class="count"><span class="img_cnt"></span> / <span class="img_amt"></span></div>
          <button class="btn btn-default btn-lg nxt animated hide" >Next <span class="icon-next"></span></button>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    <form id="hiddenForm" method="post" action='/api/report'>
      <input hidden="" class="answer0" name="q0" style="color:#444"/>   
      <input hidden="" class="answer1" name="q1" style="color:#444"/>
      <input hidden="" class="answer2" name="q2" style="color:#444"/>
      <input hidden="" class="answer3" name="q3" style="color:#444"/>
      <input hidden="" class="answer4" name="q4" style="color:#444"/>
      <input hidden="" class="answer5" name="q5" style="color:#444"/>
      <input hidden="" class="answer6" name="q6" style="color:#444"/>
      <input hidden="" class="answer7" name="q7" style="color:#444"/>
      <input hidden="" class="jwt" name="jwt"/>
      <input type="submit" class="btn btn-default btn-lg hide submit" value="送出"/>
    </form>
  </div>
</div>
</div>
<div class="animated thanks hide">
  <h1 style="font-weight:100; font-size:48px; width: 80%; margin: 0 auto;">感謝您耐心填寫！</h1>
  <form action="/">
    <button class="btn btn-lg btn-success" style="margin:20px;" >回首頁</button>
  </form>
</div>
	<script src="/assets/js/jquery-2.1.1.min.js"></script>
	<script src='https://cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
	<script>
    var multi_Ans=Array(8)
    for(var i =0; i <8 ;i++){
        multi_Ans[i]=[];
    }
    

    $.urlParam = function(name){
	    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }
    function score2text(score){
        var text=score
        switch(score.split("_")[1]){
            case 3:
                text="非常滿意";
            case 2:
                text="滿意";
            case 1:
                text="普通";
            case 0:
                text="不滿意";
        }
        return score;
    }
    $('.selector').click(function() {
        var question_number=$(this).attr('id')
        var question_val = $(this).val();
        // console.log(question_val)
        if(question_val.split("-")[1] == 0){
            if($(".q"+question_number+"-reason").val()=="")
                $('.nxt').removeClass('fadeInUp').addClass('hide fadeOutDown');
            $(".q"+question_number+"-reason").show();
        }
        else{
            // console.log("#q"+question_number+"-reason hide")
            $("input[name=reason]").hide();
            $('.answer'+question_number).html(question_val.split("-")[1]?question_val.split("-")[1]:question_val)
            $('.answer'+question_number).val(question_val.split("-")[1]?question_val.split("-")[1]:question_val.toString())
        }
    }); 
	$(function () {
        $('.jwt').html($.urlParam('jwt'));
        $('.jwt').val($.urlParam('jwt'));
	    $('input.getName').keyup('keyup', function () {
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
        $('input[name=reason]').keyup(function () {
            if($(this).val()!== "")
                $('.nxt').removeClass('hide fadeOutDown').addClass('fadeInUp');
            else
                $('.nxt').removeClass('fadeInUp').addClass('hide fadeOutDown');
        });
        $('input[name=suggestion]').keyup(function () {
            if($(this).val()!== "")
                $('.nxt').removeClass('hide fadeOutDown').addClass('fadeInUp');
            else
                $('.nxt').removeClass('fadeInUp').addClass('hide fadeOutDown');
        });
        
	    $('.nxt').click(function () {
	        $('.nxt').removeClass('fadeInUp').addClass('fadeOutDown');
	        if ($('.progress-form li').hasClass('activate')) {
	            $('p.alerted').removeClass('fadeInLeft').addClass('fadeOutUp');
	            var $activate = $('li.activate');
	            var $inactive = $('li.inactive');
	            $activate.removeClass('fadeInRightBig activate').addClass('fadeOutLeftBig');
	            $inactive.removeClass('hide inactive').addClass('activate fadeInRightBig').next().addClass('inactive');
	            var img_cnt = $('li.activate').index() + 1;
	            var img_amt = $('li.form-group').length;
	            $('.img_cnt').html(img_cnt);
	            $('.img_amt').html(img_amt);
	            var progress = $('.img_cnt').text() / $('.img_amt').text() * 100;
	            $('.progress-bar').css('width', progress + '%');
	            if ($('.img_cnt').html() == $('.img_amt').html()) {
	                $('.count, .nxt').hide();
	                $('.submit').removeClass('hide');
	            }
	        }
	    });
    });

	$(function () {
	    $('input[name=reason]').keyup(function () {
            var Value = $(this).val();
            var q_number = $(this).attr("id")
            
            $('.answer'+q_number).html(Value);
            $('.answer'+q_number).val(Value.toString());
        });

        
        
        $('input[name=service_providers_forbidden]').click(function(){
            
            var q_number = $(this).attr("id")
            multi_Ans[q_number]=[]
            $('input[name=service_providers_forbidden]:checked').each(function() {
                multi_Ans[q_number].push($(this).val());
            });
            multi_Ans[q_number].push($('.service_providers_forbidden').val())
            console.log(q_number+":"+$('.service_providers_forbidden').val());
            $('.answer'+q_number).html(multi_Ans[q_number].join(","));
            $('.answer'+q_number).val(multi_Ans[q_number].join(","));
        });
        $('.service_providers_forbidden').keyup(function(){
            
            var q_number = $(this).attr("id")
            multi_Ans[q_number]=[]
            $('input[name=service_providers_forbidden]:checked').each(function() {
                multi_Ans[q_number].push($(this).val());
            });
            multi_Ans[q_number].push($(this).val())
            console.log(q_number+"::"+$(this).val());
            $('.answer'+q_number).html(multi_Ans[q_number].join(","));
            $('.answer'+q_number).val(multi_Ans[q_number].join(","));
        });
        
	    
        $('input[name=suggestion]').keyup(function () {
	        var Value = $(this).val();
            $('.answer7').html(Value);
            $('.answer7').val(Value.toString());
	    });
	});
	</script>
</body>
</html>