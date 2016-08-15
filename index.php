<?php
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Реестр студентов</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <style type="text/css">    
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            /*padding: 5px;
            text-align: left;*/
        }
  </style>
</head>
<body>

<h3>Форма для добавления нового студента</h3>
<form action="insertNewStudent.php" method="post" id="insertForm">
    <label for="first_name">Имя</label>
    <input type="text" id="first_name" name="data[first_name]" placeholder="Введите имя" required="true"><br>
    <label for="last_name">Фамилия</label>
    <input type="text" id="last_name" name="data[last_name]" placeholder="Введите фамилию" required="true"><br>
    <label for="group_number">Учебная группа</label>
    <input type="text" id="group_number" name="data[group_number]" placeholder="Введите учебную группу" required="true"><br>
    <label for="birthday">Дата рождения</label>
    <input type="date" id="birthday" name="data[birthday_date]" required="true"><br>
    <label for="email">Email</label>
    <input type="email" id="email" name="data[email]" placeholder="Введите email адрес" required="true"><br>
    <input type="submit" name="data[submit]" value="Сохранить нового студента">
</form>
<div id="result"></div>
<br>

<h3>ТОП-10 студентов по рейтингу</h3>
<button id="showButtonTop">Обновить исходный список ТОП-10</button>
<table id="resultTableTop">
    <thead>    
    <tr>      
      <th>Id</th>
      <th>Имя</th>
      <th>Фамилия</th>
      <th>Группа</th>
      <th>Дата рождения</th>
      <th>Email</th>
      <th>IP-адрес</th>
      <th>Дата и время регистрации</th>
      <th>Характеристика</th>       
      <th>Средняя оценка</th>                    
    </tr>      
    </thead>
    <tbody> 
    </tbody>
</table>
<br>

<h3>Информация обо всех студентах</h3>
<button id="showButton">Получить список всех студентов</button>
<table id="resultTable">
    <thead>    
    <tr>      
      <th>Id</th>
      <th>Имя</th>
      <th>Фамилия</th>
      <th>Группа</th>
      <th>Дата рождения</th>
      <th>Email</th>
      <th>IP-адрес</th>
      <th>Дата и время регистрации</th>
      <th>Характеристика</th>       
      <th>Средняя оценка</th>       
      <th colspan="3">Детализация оценок</th>       
    </tr>      
    </thead>
    <tbody> 
    </tbody>
</table>


<script>

//callback handler for form submit
$("#insertForm").submit(function(e)
{
    var postData = $(this).serializeArray();    
    var formURL = $(this).attr("action");
    $.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR) 
        {
            var content = data;            
            $( "#result" ).empty().append( content );
            $('#insertForm').trigger("reset");
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails      
        }
    });
    e.preventDefault(); //STOP default action    
});



//callback handler for show all students by button click
$("#showButton").click(function(e)
{       
    $.ajax(
    {
        url : "showAllStudents.php",
        type: "POST",       
        data: { show: 'true'},  
        dataType: 'json',       
        success:function(data, textStatus, jqXHR) 
        {                    
            //info about all users
            var student = data[0];
            //info about students marks
            var marks = data[1];

            $("#resultTable tbody").empty();         

            for (var i = 0; i < student.length; i++) {  

                var len = 1;
                for (var k = 0; k < marks.length; k++) {  
                    if(marks[k]['id_student'] == student[i]['id']) {
                        len++;
                    }
                }    
 
                $("#resultTable").find('tbody')                                
                    .append($('<tr>')                  
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['id'] ))
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['first_name'] ))
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['last_name'] ))  
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['group_number'] ))
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['birthday_date'] )) 
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['email'] )) 
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['ip'] ))                  
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['registration_date'] ))     
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['description'] )) 
                    .append($('<td rowspan="'+ len +'">')
                      .append( student[i]['average_mark'] )) 
                    .append($('<td rowspan="">')
                      .append( "Предмет" )) 
                    .append($('<td rowspan="">')
                      .append( "Оценка" )) 
                    .append($('<td rowspan="">')
                      .append( "Семестр" )) 
                );   

                for (var j = 0; j < marks.length; j++) {  

                    if(marks[j]['id_student'] == student[i]['id']) {

                        $("#resultTable tbody").last().append($('<tr>')
                            .append($('<td>').append( marks[j]['mark'] ))
                            .append($('<td>').append( marks[j]['name'] ))
                            .append($('<td>').append( marks[j]['semester_number'] ))                           
                        );
                    }
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails               
        }
    });
    e.preventDefault(); //STOP default action    
});



//load top-10 students default on start page 
$( document ).ready(showTopStudents);
//reload top-10 students by click on button
$("#showButtonTop").click(showTopStudents);

function showTopStudents()
{       
    $.ajax(
    {
        url : "showTopStudents.php",
        type: "POST",       
        data: { show: 'true'},  
        dataType: 'json',       
        success:function(data, textStatus, jqXHR) 
        {                    
            //info about all top-10 students
            var student = data;                      

            $("#resultTableTop tbody").empty();         

            for (var i = 0; i < student.length; i++) {                  
 
                $("#resultTableTop").find('tbody')                                
                    .append($('<tr>')                  
                    .append($('<td>')
                      .append( student[i]['id'] ))
                    .append($('<td>')
                      .append( student[i]['first_name'] ))
                    .append($('<td>')
                      .append( student[i]['last_name'] ))  
                    .append($('<td>')
                      .append( student[i]['group_number'] ))
                    .append($('<td>')
                      .append( student[i]['birthday_date'] )) 
                    .append($('<td>')
                      .append( student[i]['email'] )) 
                    .append($('<td>')
                      .append( student[i]['ip'] ))                  
                    .append($('<td>')
                      .append( student[i]['registration_date'] ))     
                    .append($('<td>')
                      .append( student[i]['description'] )) 
                    .append($('<td>')
                      .append( student[i]['average_mark'] ))                     
                );                   
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails               
        }
    });    
}

</script>

</body>
</html>