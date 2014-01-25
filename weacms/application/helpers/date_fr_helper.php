<?php
	function getDays()
	{
		return array("Monday", "Tuesday", "Wenesday", "Thursday", "Friday", "Saturday", "Sunday");
	}
	
	/* ----------------------------------------------- */	
	
	function getDaysShort()
	{
		return array("lun","mar","mer","jeu","ven","sam","dim");
	}
	function getMonths()
	{
		return array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Décembre');
	}
	
	/* ----------------------------------------------- */	
	
	function getMonthShort()
	{
		return array('Jan','Fév','Mar','Avr','Mai','Jui','Juil','Aou','Sep','Oct','Nov','Déc');
	}

	/* ----------------------------------------------- */	
	
	function extractDate($d)
	{
		$date_year = substr($d,0,4);		// year
		$date_month = substr($d,5,2);		// month
		$date_day = substr($d,8,2);		// day
		if( substr($d,11,8) != null)
		{
			$date_hour = substr($d,11,2);		// hour 
			$date_minute = substr($d,14,2);	// minute
			$date_seconde = substr($d,17,2); 	// seconde
			
			return  array('year'=>$date_year, 'month'=> $date_month, 'day'=> $date_day, 'hour'=> $date_hour, 'minute'=> $date_minute, 'second'=> $date_seconde);
		}
		return  array('year'=>$date_year, 'month'=> $date_month, 'day'=> $date_day);
	}		
	
	/* ----------------------------------------------- */	
	
	function date_fr_to_mysql($date)
	{
		list($jour, $mois, $annee) = explode('/', $date);
		
  		return ($annee .'-'. $mois .'-'. $jour);
	}
	
	/* ----------------------------------------------- */	
	
	function format_date_diff($time)
	{
		$t = $time;
		$time = strtotime($time);
		$diff = time()-$time;

		if($diff>0)
		{
			$sec = $diff%60;
			$min = ($diff-$sec)/60%60;
			$heure = ($diff-$sec-$min*60)/3600%24;

			$minuit = mktime('0','0','0',date('m'),date('d'),date('Y'));
			$hier = mktime('0','0','0',date('m'),date('d')-1,date('Y'));

			if($diff<60) { return 'il y a '.$diff.' secondes'; }
				elseif($diff<3600) { return 'il y a '.$min.' minutes'; }
				elseif($diff<7200) { return 'il y a '.$heure.' heures et '.$min.' minutes'; }
				elseif($time>$minuit) { return 'aujourd\'hui à '.date('H:i',$time); }
				elseif($time>$hier) { return 'hier à '.date('H:i',$time); }
			
			else { return 'le '.date_month_short($t); }
		}
		else { return date_month_short($t); }
	}
	
	/* ----------------------------------------------- */	
	
	function date_month_short($d)
	{
		$date = extractDate($d);
		$listJoursShort = getDaysShort();
		$listMoisShort = getMonths();
	 
		return $listJoursShort[date('N', mktime(0, 0, 0, (int)($date['month']), $date['day'], $date['year'])) - 1].' '.$date['day'].' '.$listMoisShort[(int)$date['month']-1];
	}
	
	
	/* ----------------------------------------------- */	
	
	function date_month_hour($d)
	{
		$date = extractDate($d);
		$listJoursShort = getDaysShort();
		$listMoisShort = getMonths();
	 
		return $listJoursShort[date('N', mktime(0, 0, 0, (int)($date['month']), $date['day'], $date['year'])) - 1].' '.$date['day'].' '.$listMoisShort[(int)$date['month']-1] .' <small>('. $date['hour'] .':'. $date['minute'] .')</small>';
	}	
	
