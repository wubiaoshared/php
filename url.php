<?php
//header("content-type:text/html;charset=utf-8");


//模拟登录 
function login_post($url, $cookie, $post) { 
    $curl = curl_init();//初始化curl模块 
    curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址 
    curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息 
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息 
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中 
    curl_setopt($curl, CURLOPT_POST, 1);//post方式提交 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息 
    curl_exec($curl);//执行cURL 
    curl_close($curl);//关闭cURL资源，并且释放系统资源 

} 

//登录成功后获取数据 
function get_content($url,$cookie) { 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookie 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    $rs = curl_exec($ch); //执行cURL抓取页面内容 
    curl_close($ch); 
    return $rs; 
} 


function login(){

	//登录地址 
	$url = "https://member.expireddomains.net/login/";
	$referer="https://member.expireddomains.net/"; 
	//设置cookie保存路径 
	$cookie = dirname(__FILE__) . '/cookie_expireddomain.txt'; 
	//echo $cookie;
	 
	//设置post的数据 
	$post = array ( 

	    "login" => "Leevald", 
	    "password" => "8458245619", 
	    "rememberme" => "1", 
	    "button_submit" => "Login"
	); 
	//模拟登录 
	login_post($url, $cookie, $post);
	//echo "login success!! </br>";

} 
 
function getDomains($suffix,$date){
	//echo "pending date= {$date} ============================suffix = {$suffix} </br>";
	
	$domainarray=array();
	//判断是否已经抓取，如果已经抓取从txt文档读，如果没有抓取，抓取后存入文档
	if(file_exists($date.$suffix.".txt")){
		$fp=fopen($date.$suffix.".txt","r");
		while(!feof($fp))
		  {
		  	$keydomainarray[] =fgets($fp);
		  }
		$domainarray=$keydomainarray;
		fclose($fp);
		return $domainarray;

	}else{
		//设置cookie保存路径 
		$cookie = dirname(__FILE__) . '/cookie_expireddomain.txt'; 
		//echo $cookie;
		
		$start=0;
		$flimit=200;

		
		
		for($i=1;$i<=6;$i++) {
			//echo "获取{$i}页的所有域名........</br>";
			switch ($suffix) {
				case 'in':
					# code...
					$url="https://member.expireddomains.net/domains/pendingdelete/?start=" . $start . "&ftlds[]=124&ftlds[]=310&ftlds[]=311&ftlds[]=312&ftlds[]=313&ftlds[]=314&ftlds[]=315&flimit=" . $flimit . "&fginfo=2&fpr0=1&fenddate=" . $date;
					$key=array("school", "college", "education", "university", "hotel", "class", "foundation", "study", "hospital", "medical", "science", "medicine", "sanatorium", "scientific", "primary", "technology", "student", "teacher", "institution", "enterprise", "green", "solution", "group", "hostel", "faculty", "revolution", "baby","academy");
					break;
				case 'org':
					# code...
					
					$url="https://member.expireddomains.net/domains/pendingdelete/?start=" . $start . "&ftlds[]=4&flimit=" . $flimit . "&fginfo=2&fpr=1&fprm=10&fpr0=1&fenddate=" . $date;	
					$key=array("school", "college", "education", "university", "hotel", "class", "foundation", "study", "hospital", "medical", "science", "medicine", "sanatorium", "scientific", "primary", "technology", "student", "teacher", "institution", "enterprise", "green", "solution", "group", "hostel", "faculty", "revolution", "baby", "child", "organization","academy");
					break;
				default:
					# code...
					break;
			}
			$content = get_content($url, $cookie);

			//echo $content; 
			//删除cookie文件 
			//@unlink($cookie); 
			//匹配页面信息 
			$preg = "~<td class=\"field_domain\"><a href=\"/goto/1/(.*?)>(.*?)</a>~i"; 
			preg_match_all($preg, $content, $arr); 
			//<a href="https://www.google.com/?gws_rd=ssl#q=site:falso.com.mx" target="_blank">falso.com.mx</a>
			$domainarray = array_merge($domainarray,$arr[2]); 
			//输出内容 
			//print_r($domainarray);
			$start=$i*$flimit;
			//echo $start;
		}
		$keydomainarray=getKeydomains($domainarray,$key);
		if(!file_exists($date.$suffix.".txt")){
			foreach ($keydomainarray as $key => $value) {
				file_put_contents($date.$suffix.".txt", $value."\n\r",FILE_APPEND);
			}
		}
		
		
		return $keydomainarray;
		
		
	}
	

}

function getKeydomains($doamins,$keywords){
	//echo "获取带有关键词的域名........</br>";
	$domarr=array();
	foreach ($doamins as $keyd => $domain) {
	 	# code...
	 	foreach ($keywords as $keyk => $keyword) {
	 		# code...
	 		if(strpos($domain, $keyword) ){
	 			$domarr[]=$domain;
	 		}
	 	}
	 	
	 } 

	 return array_unique($domarr);
            

}


?>