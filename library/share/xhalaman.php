<?php 
require_once 'Zend/View.php';

class halaman {

	public function pager($totalData, $maxDisplay)
	{
		if ($totalData){
			$div = $totalData / $maxDisplay;
			$mod = $totalData%$maxDisplay;
			
			$x = explode(".",$div);
			
			if($mod == 0)
				$totalPages = $x[0];
			else
				$totalPages = $x[0] + 1;
		} else {
			$totalPages = 1;
		}
		return $totalPages;
	}

	public function showPage($totalData, $numToDisplay, $currentPage, $divId, $modul, $param1, $param2, $param3, $param4)
	{
		
		$totalPages = $this->pager($totalData, $numToDisplay);
		$totalPerPages = 3;
		$totalGroup = $totalPages / $totalPerPages ;
		$modTotalGroup = $totalPages % $totalPerPages ;

		$x = explode(".",$totalGroup);

		if($modTotalGroup == 0)
			$totalGroup = $x[0];
		else
			$totalGroup = $x[0] + 1;
		
		
		if($currentPage)
		{
			$currentGroupPage_arr =  explode(".",$currentPage / $totalPerPages);
			if($currentGroupPage_arr[1] != 0)
				$currentGroupPage = $currentGroupPage_arr[0] +1;
			else
				$currentGroupPage = $currentGroupPage_arr[0];
		}
		
		if(!$currentGroupPage)
			$currentGroupPage = 1;
		
		$indexStartPage = (($currentGroupPage - 1) * $totalPerPages ) + 1 ;
		$indexEndPage = $indexStartPage+$totalPerPages-1;
		$dataAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;
		if($currentPage != $totalPages)
		{
			$dataAkhir = $currentPage * $numToDisplay;
		}
		else
		{
			$dataAkhir = $totalData;
		}
		
		$keluaran = "";
		$keluaran = $keluaran."      <div class=\"pagination rightside\">";
		$keluaran = $keluaran." 	 	$totalData Data &bull;"; 
		
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> &bull;";				
			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto('$divId', '$modul','$totalPages','$fungsi', '$param1', '$param2', '$param3', '$param4'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> &bull;";			
		}
		
		if($currentPage == 1)
		{
			$keluaran =  $keluaran . "<span class=\"disabled\">&laquo; berikutnya</span>";
		}
		else 
		{
			$a = $currentPage -1;
			$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$divId', '$modul','$a','$param1','$param2','$param3','$param4');\">&laquo; berikutnya</a>";
		}
		
		if($totalPages <= $indexEndPage)
		{
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
				{
					$keluaran =  $keluaran . "<span class=\"current\">$a</span>";
				}
				else
				{
					$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$divId', '$modul','$a','$param1','$param2','$param3','$param4');\">$a</a>";
				}
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{	
				if($a == $currentPage)
				{
					$keluaran =  $keluaran . "<span class=\"current\">$a</span>";
				}
				else
				{
					$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$divId', '$modul','$a','$param1','$param2','$param3','$param4');\">$a</a>";
				}
			}
		}	
		
		if ($currentPage < $totalPages)
		{
			$a = $currentPage +1;
			$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage('$divId', '$modul','$a','$param1','$param2','$param3','$param4');\">sebelumnya &raquo;</a>";
		}
		else 
		{
		
			$keluaran =  $keluaran . "&nbsp;<span class=\"disabled\">sebelumnya &raquo;</span>";
		}

		$keluaran = $keluaran."      </div>";
		echo $keluaran;	
		
	}
	
	public function showPage8($totalData, $numToDisplay, $currentPage, $divId, $modul, $param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10)
	{
		
		$totalPages = $this->pager($totalData, $numToDisplay);
		$totalPerPages = 3;
		$totalGroup = $totalPages / $totalPerPages ;
		$modTotalGroup = $totalPages % $totalPerPages ;

		$x = explode(".",$totalGroup);

		if($modTotalGroup == 0)
			$totalGroup = $x[0];
		else
			$totalGroup = $x[0] + 1;
		
		
		if($currentPage)
		{
			$currentGroupPage_arr =  explode(".",$currentPage / $totalPerPages);
			if($currentGroupPage_arr[1] != 0)
				$currentGroupPage = $currentGroupPage_arr[0] +1;
			else
				$currentGroupPage = $currentGroupPage_arr[0];
		}
		
		if(!$currentGroupPage)
			$currentGroupPage = 1;
		
		$indexStartPage = (($currentGroupPage - 1) * $totalPerPages ) + 1 ;
		$indexEndPage = $indexStartPage+$totalPerPages-1;
		$dataAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;
		if($currentPage != $totalPages)
		{
			$dataAkhir = $currentPage * $numToDisplay;
		}
		else
		{
			$dataAkhir = $totalData;
		}
		
		$keluaran = "";
		$keluaran = $keluaran."      <div class=\"pagination rightside\">";
		$keluaran = $keluaran." 	 	$totalData Data &bull;"; 
		
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> &bull;";				
			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto8('$divId', '$modul','$totalPages','$fungsi', '$param1', '$param2', '$param3', '$param4', '$param5', '$param6', '$param7', '$param8', '$param9', '$param10'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> &bull;";			
		}
		
		if($currentPage == 1)
		{
			$keluaran =  $keluaran . "<span class=\"disabled\">&laquo; sebelumnya</span>";
		}
		else 
		{
			$a = $currentPage -1;
			$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage8('$divId', '$modul','$a','$param1','$param2','$param3','$param4', '$param5', '$param6', '$param7', '$param8', '$param9', '$param10');\"><strong>&#60;&#60;</strong><span class=\"page-sep\">, </span></a>";
		}
		
		if($totalPages <= $indexEndPage)
		{
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
				{
					$keluaran =  $keluaran . "<span class=\"current\">$a</span>";
				}
				else
				{
					$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage8('$divId', '$modul','$a','$param1','$param2','$param3','$param4', '$param5', '$param6', '$param7', '$param8', '$param9', '$param10');\">$a</a>";
				}
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{	
				if($a == $currentPage)
				{
					$keluaran =  $keluaran . "<span class=\"current\">$a</span>";
				}
				else
				{
					$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage8('$divId', '$modul','$a','$param1','$param2','$param3','$param4', '$param5', '$param6', '$param7', '$param8', '$param9', '$param10');\">$a</a>";
				}
			}
		}	
		
		if ($currentPage < $totalPages)
		{
			$a = $currentPage +1;
			$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage8('$divId', '$modul','$a','$param1','$param2','$param3','$param4', '$param5', '$param6', '$param7', '$param8', '$param9', '$param10');\">sebelumnya &raquo;</a>";
		}
		else 
		{
		
			$keluaran =  $keluaran . "&nbsp;<span class=\"disabled\">berikutnya &raquo;</span>";
		}

		$keluaran = $keluaran."      </div>";
		echo $keluaran;	
		
	}
	
	public function showPage3($totalData, $numToDisplay, $currentPage, $divId, $modul, $param1, $param2, $param3, $param4, $param5, $param6)
	{
		
		$totalPages = $this->pager($totalData, $numToDisplay);
		$totalPerPages = 3;
		$totalGroup = $totalPages / $totalPerPages ;
		$modTotalGroup = $totalPages % $totalPerPages ;

		$x = explode(".",$totalGroup);

		if($modTotalGroup == 0)
			$totalGroup = $x[0];
		else
			$totalGroup = $x[0] + 1;
		
		
		if($currentPage)
		{
			$currentGroupPage_arr =  explode(".",$currentPage / $totalPerPages);
			if($currentGroupPage_arr[1] != 0)
				$currentGroupPage = $currentGroupPage_arr[0] +1;
			else
				$currentGroupPage = $currentGroupPage_arr[0];
		}
		
		if(!$currentGroupPage)
			$currentGroupPage = 1;
		
		$indexStartPage = (($currentGroupPage - 1) * $totalPerPages ) + 1 ;
		$indexEndPage = $indexStartPage+$totalPerPages-1;
		$dataAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;
		if($currentPage != $totalPages)
		{
			$dataAkhir = $currentPage * $numToDisplay;
		}
		else
		{
			$dataAkhir = $totalData;
		}
		
		$keluaran = "";
		$keluaran = $keluaran."      <div class=\"pagination rightside\">";
		$keluaran = $keluaran." 	 	$totalData Data &bull;"; 
		
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> &bull;";				
			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto3('$divId', '$modul','$totalPages','$param1', '$param2', '$param3', '$param4', '$param5', '$param6'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> &bull;";			
		}
		
		if($currentPage == 1)
		{
			$keluaran =  $keluaran . "<span class=\"disabled\">&laquo; berikutnya</span>";
		}
		else 
		{
			$a = $currentPage -1;
			$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage3('$divId', '$modul','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">&laquo; berikutnya</a>";
		}
		
		if($totalPages <= $indexEndPage)
		{
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
				{
					$keluaran =  $keluaran . "<span class=\"current\">$a</span>";
				}
				else
				{
					$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage3('$divId', '$modul','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">$a</a>";
				}
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{	
				if($a == $currentPage)
				{
					$keluaran =  $keluaran . "<span class=\"current\">$a</span>";
				}
				else
				{
					$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage3('$divId', '$modul','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">$a</a>";
				}
			}
		}	
		
		if ($currentPage < $totalPages)
		{
			$a = $currentPage +1;
			$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage3('$divId', '$modul','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">sebelumnya &raquo;</a>";
		}
		else 
		{
		
			$keluaran =  $keluaran . "&nbsp;<span class=\"disabled\">sebelumnya &raquo;</span>";
		}

		$keluaran = $keluaran."      </div>";
		echo $keluaran;	
		
	}
	
	public function showPage2($totalData, $numToDisplay, $currentPage, $modul, $fungsi, $param1, $param2, $param3, $param4, $divId)
	{
		//echo "$modul";
		$totalPages = $this->pager($totalData, $numToDisplay);
		$totalPerPages = 5;
		$totalGroup = $totalPages / $totalPerPages ;
		$modTotalGroup = $totalPages % $totalPerPages ;

		$x = explode(".",$totalGroup);

		if($modTotalGroup == 0)
			$totalGroup = $x[0];
		else
			$totalGroup = $x[0] + 1;
		
		
		if($currentPage)
		{
			$currentGroupPage_arr =  explode(".",$currentPage / $totalPerPages);
			if($currentGroupPage_arr[1] != 0)
				$currentGroupPage = $currentGroupPage_arr[0] +1;
			else
				$currentGroupPage = $currentGroupPage_arr[0];
		}
		
		if(!$currentGroupPage)
			$currentGroupPage = 1;
			
		$indexStartPage = (($currentGroupPage - 1) * $totalPerPages ) + 1 ;
		$indexEndPage = $indexStartPage+$totalPerPages-1;
		$dataAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;

		if($currentPage != $totalPages)
		{
			$dataAkhir = $currentPage * $numToDisplay;
		}
		else
		{
			$dataAkhir = $totalData;
		}
		
		$keluaran = "";
		//$keluaran = $keluaran."<div id=\"ctBox\">";
		$keluaran = $keluaran."      <div class=\"left\">Data $dataAwal - $dataAkhir dari total $totalData data</div>";
		$keluaran = $keluaran."      <div class=\"pagination\">";
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	Halaman $currentPage dari $totalPages : ";			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto2('$modul','$totalPages','$fungsi', '$param1', '$param2', '$param3', '$param4', '$divId'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> : ";			
		}
		
		
		//if(($currentGroupPage > 1) && ($totalGroup != 1))
		if($currentPage > 1)
		{
			//$a = ($indexStartPage -$totalPerPages);
			$a = 1;
			//$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;<<</a></div>";
			$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\"><strong>&#60;&#60;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
		}
		
/* 		if($currentGroupPage > 1)
		{
			$a = 1;
			$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$pertama&nbsp;</a></div>";
		} */
		
		if($currentPage > 1)
			{
				$a = $currentPage -1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;<&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\"><strong>&nbsp;&#60;&nbsp;</strong><span class=\"page-sep\">, </span></a>";
			}
			
		if($totalPages <= $indexEndPage)
		{
			
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
					//$keluaran =  $keluaran . "<div class=\"pageCurrent\">&nbsp;$a&nbsp;</div>"; 
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;$a&nbsp;</a></div>";		
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;>&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\"><strong>&#62;</strong></a>";
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{
				if($a == $currentPage)
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\"><strong>&#62;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
			}
		/* if($currentGroupPage < $totalGroup)
		{
			$a = $totalPages;
			$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$terakhir&nbsp;</a></div>";
		}	 */
			//if(($totalGroup > 1) && ($currentGroupPage != $totalGroup))
			if ($currentPage <= $totalPages)
			{
				//$a = ($currentGroupPage * $totalPerPages) + 1;
				$a = $totalPages;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;>></a></div>";
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage2('$modul','$fungsi','$a','$param1','$param2','$param3','$param4', '$divId');\">&#62;&#62;<span class=\"page-sep\">, </span></a>&nbsp;";
			}
		}	
		
		
		/* $indexPage = "<div class=\"page\">Halaman $currentPage dari $totalPages  : </div>".$indexPage;
		if($totalPages > 1)
		{
			$keluaran = $keluaran."<a href=\"#\" onclick=\"return !showPopup('mn_paging', event);\" title=\"Cari Halaman\"><div class=\"pageNav\">";
		}
		$keluaran = $keluaran."			</div></a>"; */
		$keluaran = $keluaran."      </div>";
		//$keluaran = $keluaran."      </div>";
		
		return $keluaran;
	}



	public function showPage5($totalData, $numToDisplay, $currentPage, $modul, $fungsi, $param1, $param2, $param3, $param4, $param5, $param6)
	{
	//echo "totalData= ".$totalData."<br>"."numToDisplay= ".$numToDisplay."<br>"."currentPage= ".$currentPage."<br>"."modul= ".$modul."<br>"."fungsi= ".$fungsi."<br>";
	//echo "param1= ".$param1."<br>"."param2= ".$param2."<br>"."param3= ".$param3."<br>"."param4= ".$param4."<br>"."param5= ".$param5."<br>";
	
		//echo "param5= ".$param5."<br>";
		$totalPages = $this->pager($totalData, $numToDisplay);
		$totalPerPages = 5;
		$totalGroup = $totalPages / $totalPerPages ;
		$modTotalGroup = $totalPages % $totalPerPages ;

		$x = explode(".",$totalGroup);

		if($modTotalGroup == 0)
			$totalGroup = $x[0];
		else
			$totalGroup = $x[0] + 1;
		
		
		if($currentPage)
		{
			$currentGroupPage_arr =  explode(".",$currentPage / $totalPerPages);
			if($currentGroupPage_arr[1] != 0)
				$currentGroupPage = $currentGroupPage_arr[0] +1;
			else
				$currentGroupPage = $currentGroupPage_arr[0];
		}
		
		if(!$currentGroupPage)
			$currentGroupPage = 1;
			
		$indexStartPage = (($currentGroupPage - 1) * $totalPerPages ) + 1 ;
		$indexEndPage = $indexStartPage+$totalPerPages-1;
		$dataAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;
		if($currentPage != $totalPages)
		{
			$dataAkhir = $currentPage * $numToDisplay;
		}
		else
		{
			$dataAkhir = $totalData;
		}
		
		$keluaran = "";
		//$keluaran = $keluaran."<div id=\"ctBox\">";
		$keluaran = $keluaran."      <div class=\"left\">Data $dataAwal - $dataAkhir dari total $totalData data</div>";
		$keluaran = $keluaran."      <div class=\"pagination\">";
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	Halaman $currentPage dari $totalPages : ";			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto5('$modul','$totalPages','$fungsi', '$param1', '$param2', '$param3', '$param4', '$param5', '$param6'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> : ";			
		}
		
		
		//if(($currentGroupPage > 1) && ($totalGroup != 1))
		if($currentPage > 1)
		{
			//$a = ($indexStartPage -$totalPerPages);
			$a = 1;
			//$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;<<</a></div>";
			$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\"><strong>&#60;&#60;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
		}
		
/* 		if($currentGroupPage > 1)
		{
			$a = 1;
			$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$pertama&nbsp;</a></div>";
		} */
		
		if($currentPage > 1)
			{
				$a = $currentPage -1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;<&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\"><strong>&nbsp;&#60;&nbsp;</strong><span class=\"page-sep\">, </span></a>";
			}
			
		if($totalPages <= $indexEndPage)
		{
			
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
					//$keluaran =  $keluaran . "<div class=\"pageCurrent\">&nbsp;$a&nbsp;</div>"; 
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;$a&nbsp;</a></div>";		
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;>&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\"><strong>&#62;</strong></a>";
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{
				if($a == $currentPage)
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\"><strong>&#62;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
			}
		/* if($currentGroupPage < $totalGroup)
		{
			$a = $totalPages;
			$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$terakhir&nbsp;</a></div>";
		}	 */
			//if(($totalGroup > 1) && ($currentGroupPage != $totalGroup))
			if ($currentPage <= $totalPages)
			{
				//$a = ($currentGroupPage * $totalPerPages) + 1;
				$a = $totalPages;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;>></a></div>";
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage5('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6');\">&#62;&#62;<span class=\"page-sep\">, </span></a>&nbsp;";
			}
		}	
		
		
		/* $indexPage = "<div class=\"page\">Halaman $currentPage dari $totalPages  : </div>".$indexPage;
		if($totalPages > 1)
		{
			$keluaran = $keluaran."<a href=\"#\" onclick=\"return !showPopup('mn_paging', event);\" title=\"Cari Halaman\"><div class=\"pageNav\">";
		}
		$keluaran = $keluaran."			</div></a>"; */
		$keluaran = $keluaran."      </div>";
		//$keluaran = $keluaran."      </div>";
		
		return $keluaran;
	}
	
	public function showPage7($totalData, $numToDisplay, $currentPage, $modul, $fungsi, $param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
	{
	//echo "totalData= ".$totalData."<br>"."numToDisplay= ".$numToDisplay."<br>"."currentPage= ".$currentPage."<br>"."modul= ".$modul."<br>"."fungsi= ".$fungsi."<br>";
	//echo "param1= ".$param1."<br>"."param2= ".$param2."<br>"."param3= ".$param3."<br>"."param4= ".$param4."<br>"."param5= ".$param5."<br>";
	
		//echo "param5= ".$param5."<br>";
		$totalPages = $this->pager($totalData, $numToDisplay);
		$totalPerPages = 5;
		$totalGroup = $totalPages / $totalPerPages ;
		$modTotalGroup = $totalPages % $totalPerPages ;

		$x = explode(".",$totalGroup);

		if($modTotalGroup == 0)
			$totalGroup = $x[0];
		else
			$totalGroup = $x[0] + 1;
		
		
		if($currentPage)
		{
			$currentGroupPage_arr =  explode(".",$currentPage / $totalPerPages);
			if($currentGroupPage_arr[1] != 0)
				$currentGroupPage = $currentGroupPage_arr[0] +1;
			else
				$currentGroupPage = $currentGroupPage_arr[0];
		}
		
		if(!$currentGroupPage)
			$currentGroupPage = 1;
			
		$indexStartPage = (($currentGroupPage - 1) * $totalPerPages ) + 1 ;
		$indexEndPage = $indexStartPage+$totalPerPages-1;
		$dataAwal = (($currentPage - 1) * $numToDisplay ) + 1 ;
		if($currentPage != $totalPages)
		{
			$dataAkhir = $currentPage * $numToDisplay;
		}
		else
		{
			$dataAkhir = $totalData;
		}
		
		$keluaran = "";
		//$keluaran = $keluaran."<div id=\"ctBox\">";
		$keluaran = $keluaran."      <div class=\"left\">Data $dataAwal - $dataAkhir dari total $totalData data</div>";
		$keluaran = $keluaran."      <div class=\"pagination\">";
		if($totalPages == 1)
		{
			$keluaran = $keluaran." 	 	Halaman $currentPage dari $totalPages : ";			
		}
		else
		{
			$keluaran = $keluaran." 	 	<a title=\"Klik untuk langsung ke halaman ... \" onclick=\"jumpto7('$modul','$totalPages','$fungsi', '$param1', '$param2', '$param3', '$param4', '$param5', '$param6', '$param7', '$param8'); return false;\" href=\"#pageCursor\">Halaman $currentPage dari $totalPages</a> : ";			
		}
		
		
		//if(($currentGroupPage > 1) && ($totalGroup != 1))
		if($currentPage > 1)
		{
			//$a = ($indexStartPage -$totalPerPages);
			$a = 1;
			//$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;<<</a></div>";
			$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage7('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6', '$param7', '$param8');\"><strong>&#60;&#60;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
		}
		
/* 		if($currentGroupPage > 1)
		{
			$a = 1;
			$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$pertama&nbsp;</a></div>";
		} */
		
		if($currentPage > 1)
			{
				$a = $currentPage -1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;<&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage7('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6', '$param7', '$param8');\"><strong>&nbsp;&#60;&nbsp;</strong><span class=\"page-sep\">, </span></a>";
			}
			
		if($totalPages <= $indexEndPage)
		{
			
			for($a=$indexStartPage; $a <= $totalPages; $a ++)
			{
				if($a == $currentPage)
					//$keluaran =  $keluaran . "<div class=\"pageCurrent\">&nbsp;$a&nbsp;</div>"; 
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;$a&nbsp;</a></div>";		
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage7('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6', '$param7', '$param8');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;>&nbsp;</a></div>";
				$keluaran =  $keluaran . "<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage7('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6', '$param7', '$param8');\"><strong>&#62;</strong></a>";
			}
		}
		else
		{
			for($a=$indexStartPage; $a<=$indexEndPage; $a++)
			{
				if($a == $currentPage)
					$keluaran =  $keluaran . "<span><strong>&nbsp;$a&nbsp;</strong><span class=\"page-sep\">, </span></span>";
				else
				{
					$keluaran =  $keluaran . "<span><a href=\"#pageCursor\" onClick=\"javascript:gantinewPage7('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6', '$param7', '$param8');\">&nbsp;$a&nbsp;</a><span class=\"page-sep\">, </span></span>";
				}
			}
			
			if($currentPage < $totalPages)
			{
				$a = $currentPage +1;
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage7('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6', '$param7', '$param8');\"><strong>&#62;</strong><span class=\"page-sep\">, </span></a>&nbsp;";
			}
		/* if($currentGroupPage < $totalGroup)
		{
			$a = $totalPages;
			$keluaran =  $keluaran . "<div class=\"pageFL\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">$terakhir&nbsp;</a></div>";
		}	 */
			//if(($totalGroup > 1) && ($currentGroupPage != $totalGroup))
			if ($currentPage <= $totalPages)
			{
				//$a = ($currentGroupPage * $totalPerPages) + 1;
				$a = $totalPages;
				//$keluaran =  $keluaran . "<div class=\"page\"><a href=\"#viewDetail\" idreg='$req_id' svr='$req_svr' currentPage='$a' ctgr='$ctgr' key='$key' sdate='$sdate' edate='$edate' nametitle= \"$LanguangeName\">&nbsp;&nbsp;>></a></div>";
				$keluaran =  $keluaran . "&nbsp;<a href=\"#pageCursor\" onClick=\"javascript:gantinewPage7('$modul','$fungsi','$a','$param1','$param2','$param3','$param4','$param5','$param6', '$param7', '$param8');\">&#62;&#62;<span class=\"page-sep\">, </span></a>&nbsp;";
			}
		}	
		
		
		/* $indexPage = "<div class=\"page\">Halaman $currentPage dari $totalPages  : </div>".$indexPage;
		if($totalPages > 1)
		{
			$keluaran = $keluaran."<a href=\"#\" onclick=\"return !showPopup('mn_paging', event);\" title=\"Cari Halaman\"><div class=\"pageNav\">";
		}
		$keluaran = $keluaran."			</div></a>"; */
		$keluaran = $keluaran."      </div>";
		//$keluaran = $keluaran."      </div>";
		
		return $keluaran;
	}
}
?>