<?php

define( 'MIB_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'CifSnmpMibs' . DIRECTORY_SEPARATOR );

snmp_read_mib( MIB_PATH . 'ianacharset-mib' );
snmp_read_mib( MIB_PATH . 'IANA-PRINTER-MIB.txt' );
snmp_read_mib( MIB_PATH . 'Printer-MIB.txt' );
snmp_read_mib( MIB_PATH . 'HOST-RESOURCES-MIB.txt' );

/**
 * 
 **/
class CifPrinter {

	private $address,
	        $community,
			$lcd_description,
			$connected,
			$ink_levels;
	
	public function __construct($address, $community = 'public', $lcd_description = false) {
		$this->connected       = false;
		$this->address         = $address;
		$this->community       = $community;
		$this->lcd_description = $lcd_description;
		$this->collect_printer_info();
	}

	public function get_ink_levels() {
	
	}

	private function collect_printer_info() {
		// Setting quick print turns off verbose data values being returned,
		// and causes only the value we care about to be given
		snmp_set_quick_print(true);

		// Don't automatically convert enum values to their integer representations
		snmp_set_enum_print(false);

		//snmp_set_oid_numeric_print(true);
		//snmp_set_oid_numeric_print(false);

		$retval = array();
		
		// @ to suppress warning messages. We'll validate the return value later.
		$raw = @snmprealwalk($this->host, $this->community, '.1.3.6.1.2.1.43.11.1.1.6');

		// Connection failed
		if ($raw === false) {
			echo 'OOOPS';
			return $retval;
		}

		$this->connected = true;

		if (count($raw) == 0)
			return $retval; // no data

		$prefix_length = 0;
		$largest = 0;
		foreach ($raw as $key => $value) {
			echo $key . ' => ' . $value;
			if ($prefix_length == 0) {
				// don't just use $oid's length since it may be non-numeric
				$prefix_elements = count(explode('.',$oid));
				$tmp = '.' . strtok($key, '.');
				while ($prefix_elements > 1) {
					$tmp .= '.' . strtok('.');
					$prefix_elements--;
				}
				$tmp .= '.';
				$prefix_length = strlen($tmp);
			}
			$key = substr($key, $prefix_length);
			$index = explode('.', $key, 2);
			isset($retval[$index[1]]) or $retval[$index[1]] = array();
			if ($largest < $index[0]) $largest = $index[0];
			if(is_string($value)) {
				if(substr($value, 0, 1) == '"' && substr($value, -1, 1) == '"') {
					$value = substr($value, 1, -1);
				}

			}
			$retval[$index[1]][$index[0]] = $value;
		}

		if (count($retval) == 0) return ($retval); // no data

		// fill in holes and blanks the agent may "give" you
		foreach($retval as $k => $x) {
			for ($i = 1; $i <= $largest; $i++) {
				if (! isset($retval[$k][$i])) {
					$retval[$k][$i] = '';
				}
			}
			ksort($retval[$k]);
		}
		return($retval);
	}
}

class CifSnmpPrinter {

	private $host,
			$community,
			$lcdDescription,
			$printerId = 0,
			$indicators = null,
			$prnGeneral = null,
			$connected = false;

	private static $idCount = 0;

	/**
	 * Returns the next unique ID for a CIF printer.
	 *
	 * @return int The unique id for a new printer object.
	 */
	private static function getId() {
		return self::$idCount++;
	}
	
	/**
	 * Creates a new object for a CIF printer over SNMP.
	 *
	 * @param string $host The IP address of the host.
	 * @param string $community The printer's community. Defaults to 'public'. TODO What is this?
	 * @param mixed $lcdDescription TODO What is this?
	 */
	public function __construct( $host, $community = 'public', $lcdDescription = false ) {
		$this->host = $host;
		$this->community = $community;
		$this->lcdDescription = $lcdDescription;
		$this->printerId = self::getId();
	}

	/**
	 * Returns the name of the printer, or its device description if
	 * no name is available.
	 *
	 * @return string The name of the printer, or its device description.
	 */
	public function getPrinterName() {
		$data = $this->getPrinterGeneral();

		if( isset( $data['prtGeneralPrinterName'] ) )
			return $data['prtGeneralPrinterName'];
		return $data['hrDeviceDescr'];
	}

	/**
	 * TODO What does this do?
	 */
	public function getPrinterGeneral() {
		// Do nothing if we failed to connect
		if ( ! $this->connected )
			return;

		if( $this->prnGeneral == null ) {
			$prtTable = $this->snmptable( '.1.3.6.1.2.1.43.5.1' );
			$prtKeys = array_keys($prtTable);

			$devTable = $this->snmptable( '.1.3.6.1.2.1.25.3.2' );
			$devKeys = array_keys($devTable);

			$this->prnGeneral = array_merge( $prtTable[ $prtKeys[0] ], $devTable[ $devKeys[0] ] );
		}

		return $this->prnGeneral;
	}

	/**
	 * TODO What does this do/do we even want this?
	 */
	public function getBlinkScript( $line, $id ) {
		if ( $line['onPercent'] < 100 ) {
			$toRet = '<script type="text/javascript"><!--//--><![CDATA[//><!--';
			$toRet .= 'setTimeout("blinker('.$id.','.$line['onTime'].',\'gray\','.$line['offTime'].')",'.$line['onTime'].');';
			$toRet .= '//--><!]]></script>';
		}
	}

	/**
	 * TODO What does this do?
	 */
	public function getIndicators() {
		// Return cached indicators if they've already been fetched
		if ( $this->indicators !== null )
			return $this->indicators;
		
		$data = $this->snmptable( '.1.3.6.1.2.1.43.17.6' );
		$result = array();

		foreach ( $data as $linearr ) {
			$key = $linearr['prtConsoleDescription'];
			$line = array();
			$line['onTime'] = $linearr['prtConsoleOnTime'];
			$line['offTime'] = $linearr['prtConsoleOffTime'];

			if(($linearr['prtConsoleOnTime'] + $linearr['prtConsoleOffTime'])>0) {
				$line['onPercent'] = (100 * $linearr['prtConsoleOnTime']) / ($linearr['prtConsoleOnTime'] + $linearr['prtConsoleOffTime']);
			} else {
				$line['onPercent'] = 0;
			}

			$line['color'] = $linearr['prtConsoleColor'];
			$result[$key] = $line;
		}

		$this->indicators = $result;

		return $result;
	}

	public function getConsoleFullHtml() {
		// Only display an error message if we failed to connect
		if ( ! $this->connected ) {
			return '<p>Failed to connect to printer.</p>';
		}

		$data = $this->getPrinterGeneral();
		$chars = $data['prtConsoleNumberOfDisplayChars'];
		$lines = $data['prtConsoleNumberOfDisplayLines'];
		$data = $this->snmptable('.1.3.6.1.2.1.43.16.5');
		$lcd = array();
		foreach($data as $linearr) {
			$line = htmlentities($linearr['prtConsoleDisplayBufferText']);
			$lcd[] = str_replace(' ','&nbsp;',str_pad($line, $chars, ' ', STR_PAD_BOTH));
		}
		$lcd = implode('<br />',$lcd);
		$indicators = '';
		$script = '';
		$backColor = 'gray';
		$colorPerc = 0;
		$onTime = 1000;
		$offTime = 0;
		$inds = $this->getIndicators();
		foreach($inds as $name => $line) {
			if(is_string($this->lcdDescription)) {
				if($name == $this->lcdDescription) {
					$perc = $line['onPercent'];
					if($perc > 0) {
						$backColor = $line['color'];
						$colorPerc = $perc;
						if($perc < 100) {
							$onTime = $line['onTime'];
							$offTime = $line['offTime'];
						}
					}
					continue;
				}
			}
			$name = ucwords(strtolower($name));
			$prId = self::getId();
			$title = $name.': '.$line['color'];
			if($line['onPercent'] == 0) {
				$line['color'] = 'gray';
				$title = $name.': [off]';
			}elseif($line['onPercent'] < 100) {
				$title .= ' (blinking)';
				$script .='<script type="text/javascript" language="javascript"><!--'."\n";
				$script .='function pr'.$prId.'Off() {'."\n";
				$script .='    document.getElementById("prBox'.$prId.'").style.backgroundColor="gray";'."\n";
				$script .='    setTimeout("pr'.$prId.'On()",'.$line['offTime'].");\n";
				$script .='}'."\n";
				$script .='function pr'.$prId.'On() {'."\n";
				$script .='    document.getElementById("prBox'.$prId.'").style.backgroundColor="'.$line['color']."\";\n";
				$script .='    setTimeout("pr'.$prId.'Off()",'.$line['onTime'].");\n";
				$script .='}'."\n";
				$script .='setTimeout("pr'.$prId.'Off()",'.$line['onTime'].");\n";
				$script .='// --></script>';
			}
			if($this->lcdDescription === true) {
				if($line['onPercent'] > $colorPerc) {
					$backColor = $line['color'];
					$colorPerc = $line['onPercent'];
					if($colorPerc < 100) {
						$onTime = $line['onTime'];
						$offTime = $line['offTime'];
					}
				}
			}
			$indicators.= '<tr title="'.$title.'"><td style="white-space:nowrap;" colspan="2"><div style="float: left; border-style:inset; border-width: medium; display: block; border-color: gray; color: black; background-color: '.$line['color'].'; width: 1em; height: 1em; -webkit-border-radius: 1em; -moz-border-radius: 1em; border-radius: 1em; margin-right: .25em;" id="prBox'.$prId.'" title="'.$title.'">&nbsp;</div>'.$name.'</td></tr>';
		}
		#if($colorPerc == 0) {
		#	$colorPerc = 100;
		#}
		$prId = self::getId();
		$text = '<table><tr><th colspan="2">'.$this->getPrinterName().'</th></tr><tr><td><div style="font-size: larger; display: block; float: left; font-family: monospace; border-style:inset; border-width: medium; border-color: gray; color: black; background-color: '.$backColor.';" id="prBox'.$prId.'">'.$lcd.'</div></td><td>&nbsp;</td></tr>';
		$text .= $indicators.'</table>';

		if($colorPerc < 100) {
			$text.='<script type="text/javascript" language="javascript"><!--'."\n";
			$text.='function pr'.$prId.'Off() {'."\n";
			$text .='    document.getElementById("prBox'.$prId.'").style.backgroundColor="gray";'."\n";
			$text .='    setTimeout("pr'.$prId.'On()",'.$offTime.");\n";
			$text .='}'."\n";
			$text.='function pr'.$prId.'On() {'."\n";
			$text .='    document.getElementById("prBox'.$prId.'").style.backgroundColor="'.$backColor."\";\n";
			$text .='    setTimeout("pr'.$prId.'Off()",'.$onTime.");\n";
			$text .='}'."\n";
			$text .='setTimeout("pr'.$prId.'Off()",'.$line['onTime'].");\n";
			$text .='// --></script>';
		}

		return $text.$script;
	}

	public function getIndicatorsDisplayHtml() {
		$text = '<table>';
		$script = '';
		$inds = $this->getIndicators();
		foreach($inds as $name => $line) {
			if(is_string($this->lcdDescription)) {
				if($name == $this->lcdDescription) {
					continue;
				}
			}
			$prId = self::getId();
			$title = $name.': '.$line['color'];
			if($line['onPercent'] == 0) {
				$line['color'] = 'gray';
				$title = $name.': [off]';
			}elseif($line['onPercent'] < 100) {
				$title .= ' (blinking)';
				$script.='<script type="text/javascript" language="javascript"><!--'."\n";
				$script .= 'function pr'.$prId.'Off() {'."\n";
				$script .= '    document.getElementById("prBox'.$prId.'").style.backgroundColor="transparent";'."\n";
				$script .= '    setTimeout("pr'.$prId.'On()",'.$offTime.");\n";
				$script .= '}'."\n";
				$script .= 'function pr'.$prId.'On() {'."\n";
				$script .= '    document.getElementById("prBox'.$prId.'").style.backgroundColor="'.$line['color']."\";\n";
				$script .= '    setTimeout("pr'.$prId.'Off()",'.$onTime.");\n";
				$script .= '}'."\n";
				$script .= 'setTimeout("pr'.$prId.'Off()",'.$onTime.");\n";
				$script .= '// --></script>';
			}
			$text.= '<tr title="'.$title.'"><td style="border-style:inset; border-width: medium; display:block; border-color: gray; color: black; background-color: '.$line['color'].'; width: 1em; height: 1em; -webkit-border-radius: 1em; -moz-border-radius: 1em; border-radius: 1em;" id="prBox'.$prId.'" title="'.$title.'"></td><td title="'.$title.'">'.$name.'</td></tr>';
		}
		$text .= '</table>'.$script;
		return $text;
	}

	public function getConsoleDisplayHtml() {
		$data = $this->getPrinterGeneral();
		$chars = $data['prtConsoleNumberOfDisplayChars'];
		$lines = $data['prtConsoleNumberOfDisplayLines'];
		$data = $this->snmptable('.1.3.6.1.2.1.43.16.5');
		$text = array();
		foreach($data as $linearr) {
			$line = $linearr['prtConsoleDisplayBufferText'];
			$text[] = str_replace(' ','&nbsp;',str_pad($line, $chars, ' ', STR_PAD_BOTH));
		}
		$text = implode('<br />',$text);
		$backColor = 'gray';
		$colorPerc = 0;
		$onTime = 1000;
		$offTime = 0;
		$inds = $this->getIndicators();
		if(is_string($this->lcdDescription)) {
			if(array_key_exists($this->lcdDescription, $inds)) {
				$line = $inds[$this->lcdDescription];
				$perc = $line['onPercent'];
				if($perc > 0) {
					$backColor = $line['color'];
					$colorPerc = $perc;
					if($perc < 100) {
						$onTime = $line['onTime'];
						$offTime = $line['offTime'];
					}
				}
			}
		} else if($this->lcdDescription === true) {
			foreach($inds as $line) {
				if($line['onPercent'] > $colorPerc) {
					$backColor = $line['color'];
					$colorPerc = $line['onPercent'];
					if($colorPerc < 100) {
						$onTime = $line['onTime'];
						$offTime = $line['offTime'];
					}
				}
			}
		}
		if($colorPerc == 0) {
			$colorPerc = 100;
		}
		$prId = self::getId();
		$text = '<table><tr><td style="font-size: larger; font-family: monospace; border-style:inset; border-width: medium; display:block; border-color: gray; color: black; background-color: '.$backColor.';" id="prBox'.$prId.'">'.$text.'</td></tr></table>';
		if($colorPerc < 100) {
			$text.='<script type="text/javascript" language="javascript"><!--'."\n";
			$text.='function pr'.$prId.'Off() {'."\n";
			$text .='    document.getElementById("prBox'.$prId.'").style.backgroundColor="gray";'."\n";
			$text .='    setTimeout("pr'.$prId.'On()",'.$offTime.");\n";
			$text .='}'."\n";
			$text.='function pr'.$prId.'On() {'."\n";
			$text .='    document.getElementById("prBox'.$prId.'").style.backgroundColor="'.$backColor."\";\n";
			$text .='    setTimeout("pr'.$prId.'Off()",'.$onTime.");\n";
			$text .='}'."\n";
			$text .='setTimeout("pr'.$prId.'Off()",'.$onTime.");\n";
			$text .='// --></script>';
		}
		return $text;
	}

	public function snmptable($oid) {
		return self::static_snmptable($this->host, $this->community, $oid);
	}

	public static function static_snmptable($host, $community, $oid) {
		snmp_set_quick_print(true);
		snmp_set_enum_print(false);
		snmp_set_oid_numeric_print(true);
		snmp_set_oid_numeric_print(false);

		$retval = array();
		// @ to suppress warning messages. We'll validate the return value later.
		$raw = @snmprealwalk($host, $community, $oid);

		// Connection failed
		if ( $raw === false )
			return $retval;

		$this->connected = true;

		if (count($raw) == 0)
			return $retval; // no data

		$prefix_length = 0;
		$largest = 0;
		foreach ($raw as $key => $value) {
			if ($prefix_length == 0) {
				// don't just use $oid's length since it may be non-numeric
				$prefix_elements = count(explode('.',$oid));
				$tmp = '.' . strtok($key, '.');
				while ($prefix_elements > 1) {
					$tmp .= '.' . strtok('.');
					$prefix_elements--;
				}
				$tmp .= '.';
				$prefix_length = strlen($tmp);
			}
			$key = substr($key, $prefix_length);
			$index = explode('.', $key, 2);
			isset($retval[$index[1]]) or $retval[$index[1]] = array();
			if ($largest < $index[0]) $largest = $index[0];
			if(is_string($value)) {
				if(substr($value, 0, 1) == '"' && substr($value, -1, 1) == '"') {
					$value = substr($value, 1, -1);
				}

			}
			$retval[$index[1]][$index[0]] = $value;
		}

		if (count($retval) == 0) return ($retval); // no data

		// fill in holes and blanks the agent may "give" you
		foreach($retval as $k => $x) {
			for ($i = 1; $i <= $largest; $i++) {
				if (! isset($retval[$k][$i])) {
					$retval[$k][$i] = '';
				}
			}
			ksort($retval[$k]);
		}
		return($retval);
	}

	public function getAlertsHtml() {
		$alertTable = $this->snmptable('.1.3.6.1.2.1.43.18.1');
		$toRet = array();
		//		$header = '<table><tr><th>Alert Code</th><th>Description</th><th>Severity</th></tr>';
		foreach($alertTable as $data) {
			if(array_key_exists('prtAlertDescription',$data)) {
				if(strlen($data['prtAlertDescription'])<1) {
					continue;
				}
			} else {
				if(strlen($data['prtAlertCode'])<1) {
					continue;
				}
				if(is_numeric($data['prtAlertCode'])) {
					if($data['prtAlertCode'] < 1) {
						continue;
					}
				}
			}
			//Disabled for now
			if(array_key_exists('prtAlertGroup', $data) && array_key_exists('prtAlertGroupIndex', $data) && false) {
				//				$tdo = '<td><a href="#pr'.$this->printerId.$data['prtAlertGroup'].$data['prtAlertGroupIndex'].'">';
				//				$tdc = '</a></td>';
				$tdo = '<a href="#pr'.$this->printerId.$data['prtAlertGroup'].$data['prtAlertGroupIndex'].'">';
				$tdc = '</a>';
			} else {
				//				$tdo = '<td>';
				//				$tdc = '</td>';
				$tdo = '';
				$tdc = '';
			}
			//			$toRet[] = '<tr>'.$tdo.self::codeToDescription($data['prtAlertCode']).$tdc.$tdo.$data['prtAlertDescription'].$tdc.$tdo.$data['prtAlertSeverityLevel'].$tdc.'</tr>';
			$toRet[] = $tdo.ucwords($data['prtAlertSeverityLevel']).' alert: '.$data['prtAlertDescription'].' ('.self::codeToDescription($data['prtAlertCode']).')'.$tdc;
		}
		if(count($toRet) > 0) {
			//			return $header.join('', $toRet).'</table>';
			return '<ul><li>'.join('</li><li>', $toRet).'</li></ul>';
		} else {
			return '';
		}
	}

	public function getInputsHtml() {
		$inputTable = $this->snmptable('.1.3.6.1.2.1.43.8.2');
		$toRet = array();
		$header = '<table><tr><th>Input Tray</th><th>Status</th><th>Current Level</th><th>Maximum Level</th></tr>';
		foreach($inputTable as $data) {
			if(array_key_exists('prtInputDescription', $data)) {
				$name = $data['prtInputDescription'];
			} else {
				$name = $data['prtInputName'];
			}
			$status = self::SubUnitStatus($data['prtInputStatus']);
			$unit = $data['prtInputCapacityUnit'];
			$remaining = self::CapacityOrLevel($data['prtInputCurrentLevel'],$unit,true);
			$maximum = self::CapacityOrLevel($data['prtInputMaxCapacity'],$unit,true);
			$toRet[] = "<tr><td>$name</td><td>$status</td><td>$remaining</td><td>$maximum</td></tr>";
		}
		if(count($toRet) > 0) {
			return $header.join('',$toRet).'</table>';
		}
		return '';
	}
	
	public function getSuppliesHtml() {
		$suppliesTable = $this->snmptable('.1.3.6.1.2.1.43.11.1');
		$toRet = array();
		$header = '<table><tr><th>Type</th><th>Current Level</th><th>Maximum Level</th><th>Description</th></tr>';
		foreach($suppliesTable as $data) {
			$type = ucwords(self::codeToDescription(str_replace('opc', 'opticalPhotoconductor', $data['prtMarkerSuppliesType'])));
			$unit = self::codeToDescription($data['prtMarkerSuppliesSupplyUnit']);
			$remaining = self::CapacityOrLevel($data['prtMarkerSuppliesLevel'], $unit, true);
			$maximum = self::CapacityOrLevel($data['prtMarkerSuppliesMaxCapacity'], $unit, true);
			$desc = $data['prtMarkerSuppliesDescription'];
			$toRet[] = "<tr><td>$type</td><td>$remaining</td><td>$maximum</td><td>$desc</td></tr>";
		}
		if(count($toRet) > 0) {
			return $header.join('',$toRet).'</table>';
		}
		return '';
	}

	public static function CapacityOrLevel($suppliesLevel, $unit = '', $isSupply = true) {
		//        "The current level if this supply is a container; the remaining
		//        space if this supply is a receptacle.  If this supply
		//        container/receptacle can reliably sense this value, the value
		//        is reported by the printer and is read-only; otherwise, the
		//        value may be written (by a Remote Control Panel or a Management
		//        Application).  The value (-1) means other and specifically
		//        indicates that the sub-unit places no restrictions on this
		//        parameter.  The value (-2) means unknown.  A value of (-3) means
		//        that the printer knows that there is some supply/remaining
		//        space, respectively."

		if($unit != '') {
			$unit = ' '.$unit;
		}

		switch($suppliesLevel) {
			case -1:
				if($isSupply) {
					return '(other)'.$unit;
				} else {
					return 'Unlimited'.$unit;
				}
			case -2:
				return 'Unknown';//.$unit;
			case -3:
				return 'some'.$unit.' remaining';
			case 0:
				return 'Empty';
			default:
				return ((string)$suppliesLevel).$unit;
		}
	}

	public static function SubUnitStatus($statInt, $includeDetail = false) {
		//        Availability                           Value
		//
		//            Available and Idle                  0       0000'b
		//            Available and Standby               2       0010'b
		//            Available and Active                4       0100'b
		//            Available and Busy                  6       0110'b
		//            Unavailable and OnRequest           1       0001'b
		//            Unavailable because Broken          3       0011'b
		//            Unknown                             5       0101'b
		//
		//        Non-Critical
		//            No Non-Critical Alerts              0       0000'b
		//            Non-Critical Alerts                 8       1000'b
		//
		//        Critical
		//
		//            No Critical Alerts                  0       0000'b
		//
		//
		//            Critical Alerts                    16     1 0000'b
		//
		//        On-Line
		//
		//            State is On-Line                    0       0000'b
		//            State is Off-Line                  32    10 0000'b
		//
		//        Transitioning
		//
		//            At intended state                   0       0000'b
		//            Transitioning to intended state    64   100 0000'b"
		//
		//
		$avail = ($statInt & 0x07);
		switch ($avail) {
			case 0:
				$status = 'Idle';
				break;
			case 1:
				$status = 'Not Available';
				break;
			case 2:
				$status = 'Standby';
				break;
			case 3:
				$status = 'Broken';
				break;
			case 4:
				$status = 'Active';
				break;
			case 5:
				$status = 'Unknown';
				break;
			case 6:
				$status = 'Busy';
				break;
			default:
				$status = "Unknown (raw value: $avail)";
				break;
		}
		$nonCrit = ($statInt & 0x08) == 0x08;
		$crit = ($statInt & 0x10) == 0x10;
		$offLine = ($statInt & 0x20) == 0x20;
		$transitioning = ($statInt & 0x40) == 0x40;

		if($includeDetail) {

			if($offLine) {
				$status.='; offline';
			}

			//		if($crit) {
			//			$status.='; has critical problem';
			//		}
			//
			//		if($nonCrit) {
			//			$status.='; has non-critical problem';
			//		}
			//
			//		if($transitioning) {
			//			$status.='; is in transition';
			//		}

			if($crit || $nonCrit) {
				$status.='; has problem';
			}
		}
		return $status;
	}

	public static function codeToDescription($code) {
		return trim(strtolower(join(' ',preg_split('/([A-Z](?:[a-z]+))/',$code, 0, PREG_SPLIT_DELIM_CAPTURE))));
	}

}
