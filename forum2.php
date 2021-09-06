<?php
	include ("connect_database.php");

	// 建立與MySQL資料庫的連線
	$link = mysqli_connect($hostname, $username, $password, $database) OR die("Error: Unable to connect to MySQL.");
	// 設定編碼方式為UTF-8
	
	// 另一種寫法	mysqli_query($link, "SET NAMES utf8");
	mysqli_set_charset($link, "utf8");
?>

<?php

	if(isset($_GET["next"]))//判斷$_GET["next"]是否存在
	{
		$next=$_COOKIE["forum"]-3;//把$_COOKIE["forum"]-3, 因為要顯示下三筆資料
		Setcookie("forum", "".$next."", time()+3600);//設定$_COOKIE["forum"]裡面的ID值要-3
		echo "".$next."";
		Header('Location: forum2.php');//務必要重新導向,不然COOKIE會LAG~
		exit;//在每個重定向之後都必須加上“exit”,避免發生錯誤後，繼續執行。
	}

?>


<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
<!--    讓網頁不會自動因手機螢幕變小而扭曲，使得RWD網頁能正常執行-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Robert's shopping mall</title>
    <link href="first2.css" rel=stylesheet>
	
	<!-- 滑鼠的游標,查網路,加上去的~ 來源:http://www.cursors-4u.com/cursor/2012/02/12/chrome-pointer.html-->	
	<style type="text/css">* {cursor: url(http://cur.cursors-4u.net/cursors/cur-11/cur1054.cur), auto !important;}
	</style><a href="http://www.cursors-4u.com/cursor/2012/02/12/chrome-pointer.html" 
	target="_blank" title="Chrome Pointer"><img src="http://cur.cursors-4u.net/cursor.png" 
	border="0" alt="Chrome Pointer" style="position:absolute; top: 0px; right: 0px;" /></a>

		
	
	<!--Start:插入jQuery-->
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
	<!--End:插入jQuery-->
	<!--Start:插入TOP套件-->
	<script type='text/javascript'>
	/* 返回最上面的Javascript不是自己寫的,來源:https://ezbox.idv.tw/131/back-to-top-button-without-images/*/
	$(function() {
		/* 按下GoTop按鈕時的事件 */
		$('#gotop').click(function(){
			$('html,body').animate({ scrollTop: 0 }, 'slow');   /* 返回到最頂上 */
			return false;
		});
		
		/* 偵測卷軸滑動時&#65292;往下滑超過400px就讓GoTop按鈕出現 */
		$(window).scroll(function() {
			if ( $(this).scrollTop() > 100){
				$('#gotop').fadeIn();
			} else {
				$('#gotop').fadeOut();
			}
		});
	});
	
	
	
	</script>
	<!--END:插入TOP套件-->



</head>
<body id="bg">
	
		<!--返回最上面的按鈕-->
		<div id='gotop'><center>^</center></div>
		
		<div >
			
			<!--右邊的飄浮選單-->
			
			<div id="cart">
				<span class="bold_black"><a href="cart.php">&nbsp;&nbsp;➤購物車</a></span>	
				<br />
				<a href="cart.php"><img src="images/cart.png" width="120px" height="120px"></a>
				<br />
				<?php 	
					session_start(); //session開始
					if(isset($_COOKIE["name"]))//判斷是否登入,因為若有登入的話,COOKIE裡面會有名字
					{
						echo "<span class='font2'>&nbsp;&nbsp;姓名: </span>";	
						echo "<span class='blue'>".$_COOKIE["name"]."</span>";	

					}		
					else
					{
						echo "<span class='blue'>&nbsp;&nbsp;你還沒登入</span>";
					}
				?>
			</div>
			
			<div id="MyBlog">
			
				<span class="font1"><a href="index.php">➤首頁</a></span>	
				<br />
				<span class="font1"><a href="shop.php">➤商場</a></span>				
				<br />
				<span class="font1"><a href="login.php">➤登入</a></span>
				<br />
				<span class="font1"><a href="forum.php">➤留言板</a></span>
				<br />
				<span class="font1"><a href="register.php">➤註冊</a></span>
				<br />
				<span class="font1"><a href="disaster.php">➤防災教育</a></span>
				<br />
				<span class="font1"><a href="order.php">➤訂單</a></span>
				<br />
				<span class="font1"><a href="member.php">➤會員</a></span>
				
			</div>
			
			
			<!--上方的圖片,自己做的~-->
			<header><a href="index.php"><img src="images/bg2.png"  ></a></header>
			
			
			<!--按鈕,CSS裡面的語法是自己寫的-->
			<nav>
				<a href="index.php"><button class="bt"><span>首頁</span></button></a>
				<a href="register.php"><button class="bt"><span>註冊</span></button></a>
				<a href="login.php"><button class="bt"><span>登入</span></button></a>
				<a href="forum.php"><button class="bt"><span>留言板</span></button></a>
				<a href="shop.php"><button class="bt"><span>商場</span></button></a>
				<a href="disaster.php"><button class="bt"><span>防災</span></button></a>
				<a href="order.php"><button class="bt"><span>訂單</span></button></a>
				<a href="member.php"><button class="bt"><span>會員</span></button></a>
				
			</nav>
			
			
			<h1 id="h1">留言板</h1>
			
			
	
			
					
			<aside class="forum">
			
			<?php	
			
			//插入資料進user table;";
			if(isset($_POST["submit"]))//若使用者按下"新增"按鈕,就進來
			{	
				if (empty($_POST["title"])||empty($_POST["article"]))//若標題和內容 有任一個是空白的
				{
					echo"<span class='red'>標題和內容務必都要填寫喔!!</span><br />";
				}
				else 
				{
					$title = $_POST['title'];
					$article = $_POST['article'];

					if(isset($_SESSION['judgement'])&&isset($_COOKIE["name"])) //判斷是否登入, 若有登入就進來
					{
						// 把名字、標題、內容插入 `forum` (name, Title, article) 裡面
						$query = "INSERT INTO `forum` (name, Title, article) 
									VALUES ('".$_COOKIE['name']."','$title', '$article') ;";
						$result = mysqli_query($link, $query) or die("Connect DB Table Error!");
					}
					else
					{
						echo"<span class='red'>請先登入唷!</span><br />";
					}
					
				}
	
			}								
			?>
			
			
			
			
			<span class="font2"> <?php echo "姓名 : "; ?> </span>
									
			<?php 	
				if(isset($_COOKIE["name"])) //判斷是否登入,因為若有登入的話,COOKIE裡面會有名字
				{
					
					echo "<span class='blue'>".$_COOKIE["name"]."</span>"; 
				}
				else 
				{
					echo "<span class='blue'>你還沒登入!</span>"; 
				}
			?>
			
			<center>
				<table border='1';>
					
					<form action="forum.php" method="post">
					<tr>
						<td><span class="font1">標題</span>	</td>
						<td><input  type="text" name="title" style="border: 1px solid #fff;"/></td>
					</tr>		
					<tr>
						<td><span class="font1">內容</span>	</td>
						<td><textarea name="article"  rows="15" style="border: 1px solid #fff;"></textarea></td>
					</tr>
					<tr>
						<td colspan="2"><button type="submit" name="submit" value="submit" class="bt"><span>新增</span></button></td>	
					</tr>
					</form>	
					
				</table>
			</center>
			</aside>
			
			
			
			
			<article class="forum">
			<?php	
				$previous=$_COOKIE["forum"]-3;//把$_COOKIE["forum"]-3傳給$previous
				
				//只SELECT  ID介於$_COOKIE["forum"]和$_COOKIE["forum"]-3之間的資料~
				$query1 = "select * from `forum` where `ID` <= ".$_COOKIE["forum"]." and `ID` > ".$previous." order by `ID` DESC;";
				$result1 = mysqli_query($link, $query1) or die("Connect DB Table Error!");
				
				
				while ($row=mysqli_fetch_array($result1))
				{
					
					echo "<table style='border:5px #800000 groove;' cellpadding='5' border='1'>";
					echo "<tr>";
					echo "<td rowspan='2' align='center'><span class='small_blue'>".$row['name']."</span></td>";
					echo "<td><span class='small'>標題 : </td>	<td>".$row['Title']."</span></td>";	
					echo "</tr>";
					
					//nl2br指令,讓留言可以換行
					echo "</tr>";
					echo "<td><span class='small'>內容 : </td>	<td>".nl2br($row['article'])."</span></td></tr>";
					echo "</tr>";
					
					echo "<br />";
					echo "</table>";	

				}
				
				// 釋放結果集($result)所佔用的記憶體。(若無釋放，程式可能會錯誤，尤其是用"SELECT ..."的時候)
				mysqli_free_result($result1);
					
					
				// 關閉與MySQL資料庫的連線
				mysqli_close($link);
			
			?>
			
			<br />
			<?php
			{	
				echo "<form action='' method='get'>";
				echo "<button type='submit' name='next' value='submit' class='bt'><span>下頁</span></button>";
				echo "</form>";
			}
			?>
			</article>
			
			
			<footer>網站製作: Robert's shopping mall</footer>		

					
		</div>
		
		
	
	
</body>
</html>
