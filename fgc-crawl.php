<?php

$toMajor = array(
	'101' => 'Matematika',
	'102' => 'Fisika',
	'103' => 'Astronomi',
	'104' => 'Mikrobiologi',
	'105' => 'Kimia',
	'106' => 'Biologi',
	'107' => 'Sains dan Teknologi Farmasi',
	'112' => 'Rekayasa Hayati',
	'114' => 'Rekayasa Pertanian',
	'115' => 'Rekayasa Kehutanan',
	'116' => 'Farmasi Klinik dan Komunitas',
	'120' => 'Teknik Geologi',
	'121' => 'Teknik Pertambangan',
	'122' => 'Teknik Perminyakan',
	'123' => 'Teknik Geofisika',
	'124' => 'Geofisika',
	'125' => 'Teknik Metalurgi',
	'128' => 'Meteorologi',
	'129' => 'Oseanografi',
	'130' => 'Teknik Kimia',
	'131' => 'Teknik Mesin',
	'132' => 'Teknik Elektro',
	'133' => 'Teknik Fisika',
	'134' => 'Teknik Industri',
	'135' => 'Teknik Informatika',
	'136' => 'Aeronotika dan Astronotika',
	'137' => 'Teknik Material',
	'143' => 'Teknik Pangan',
	'144' => 'Manajemen Rekayasa Industri',
	'145' => 'Teknik Bioenergi dan Kemurgi',
	'150' => 'Teknik Sipil',
	'151' => 'Teknik Geodesi dan Geomatika',
	'152' => 'Arsitektur',
	'153' => 'Teknik Lingkungan',
	'154' => 'Perencanaan Wilayah dan Kota',
	'155' => 'Teknik Kelautan',
	'157' => 'Rekayasa Infrastruktur Lingkungan',
	'158' => 'Teknik dan Pengelolaan Sumber Daya Air',
	'160' => 'Tahap Tahun Pertama FMIPA',
	'161' => 'Tahap Tahun Pertama SITH',
	'162' => 'Tahap Tahun Pertama SF',
	'163' => 'Tahap Tahun Pertama FITB',
	'164' => 'Tahap Tahun Pertama FTTM',
	'165' => 'Tahap Tahun Pertama STEI',
	'166' => 'Tahap Tahun Pertama FTSL',
	'167' => 'Tahap Tahun Pertama FTI',
	'168' => 'Tahap Tahun Pertama FSRD',
	'169' => 'Tahap Tahun Pertama FTMD',
	'170' => 'Seni Rupa',
	'172' => 'Kriya',
	'173' => 'Desain Interior',
	'174' => 'Desain Komunikasi Visual',
	'175' => 'Desain Produk',
	'179' => 'MKDU',
	'180' => 'Teknik Tenaga Listrik',
	'181' => 'Teknik Telekomunikasi',
	'182' => 'Sistem dan Teknologi Informasi',
	'183' => 'Teknik Biomedis',
	'190' => 'Manajemen',
	'192' => 'Kewirausahaan',
	'197' => 'Tahap Tahun Pertama SBM',
	'199' => 'Tahap Tahun Pertama SAPPK'
);

$tpbCodes = [
    '160', // FMIPA
	'161', // SITH
	'162', // SF
	'163', // FITB
	'164', // FTTM
	'165', // STEI
	'166', // FTSL
	'167', // FTI
	'168', // FSRD
	'169', // FTMD
	'190', // SBM
	'199'  // SAPPK
];

foreach ($tpbCodes as $code){
	
	// 2013 to 2017
	for ($year = 13; $year <= 17; $year++){
		
		// For every 5 contiguous not-founds, break $studentNumber iteration.
		$health = 5;
		
		for ($studentNumber = 1; $studentNumber <= 600; $studentNumber++){
			
			$nim = $code . $year . sprintf('%03d', $studentNumber);

			$opts = array('http' =>
				array(
					'method'  => 'POST',
					'header'  => array(
						// Provide your logged-in cookie for nic.itb.ac.id.
						'Cookie: YOUR_COOKIE_HERE',
						'Content-type: application/x-www-form-urlencoded'
					),
					'content' => http_build_query(array('uid' => $nim))
				)
			);
			
			$context = stream_context_create($opts);
			$result = file_get_contents('https://nic.itb.ac.id/manajemen-akun/pengecekan-user', false, $context);
			
			preg_match("'<td>NIM</td>
					<td>:</td>
					<td>(.*?)</td>
					</tr>

  					<tr>
    					<td>Nama Lengkap </td>
    					<td>:</td>
    					<td>(.*?)</td>
	  				</tr>
	  				<tr>
    					<td>Tipe user </td>
    					<td>:</td>
    					<td>(.*?)</td>
  					</tr>
  					<tr>
    					<td>Email ITB </td>
    					<td>:</td>
    					<td>(.*?)</td>
	  				</tr>
                                        
	  				<tr>
    					<td>Email non ITB</td>
    					<td>:</td>
    					<td>(.*?)</td>
	  				</tr>'si", $result, $match);
			
			if ($match){
				
				// When found, reset 'health'.
				$health = 5;
				
				$nims = explode(', ', $match[1]);
				$name = $match[2];
				$status = $match[3];
				$itbEmail = str_replace(array('(dot)', '(at)'), array('.', '@'), $match[4]);
				$nonItbEmail = str_replace(array('(dot)', '(at)'), array('.', '@'), $match[5]);
				
				if (empty($nims[1])){
					$major = $toMajor[substr($nims[0], 0, 3)];
					$nims[1] = '';
				} else {
					$major = $toMajor[substr($nims[1], 0, 3)];
				}
				
				// What to do with the data?
				echo ' ', $nims[0], ' ', $nims[1], ' [', $status, ' - ', $major, "]\n", $name, "\n", $itbEmail, ' | ', $nonItbEmail, "\n\n";
				
			} else
				// Reduce 'health' when the corresponding NIM isn't found.
				$health = $health - 1;
			
			if ($health == 0)
				// When $studentNumber exceeds actual student number.
				break;
						
		}
		
	}
	
}

curl_close($ch);
echo "Done.\n"

?>