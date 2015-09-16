<?php 
/*******************************************************************************
 *
 * The MIT License (MIT)
 * 
 * Copyright (c) 2012 Samarth Parikh (samarthp@ymail.com)
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 ******************************************************************************/ ?>
<?php

/* A script to merge two XML files constructively. */

$i = 0;

/* XML File Paths */
$Old_XML = "OLD_XML_File.xml"; /* Path to Old XML File. Values will be read from this file. */
$New_XML = "New_XML_File.xml"; /* Path to New XML File. Values for the same fields will be 
				  updated into this file from Old XML File but the XML file
				  structure used will be of this New XML File. */
$Tmp_XML = "$Old_XML.tmp";
$Merged_XML = "Merged_XML_File.xml"; /* Path to generated new XML File. */
$Schema_XSD = ""; /* Path to Schema XSD File. This file will be used to vaildate
		     the generated new XML file. This field is optional and can be
		     set as blank if it is not needed i.e. if validation is not 
		     required. */

$Same_Field_List = array("CamName"); /* List of Parent/Child field names in ascending order of their appearance
					in XML file which are having same field names. Usually, this needs
					to be empty unless you have any field name that is being repeated
					multiple times in your XML file. 
NOTE: If the same field list is not set or is set but the field name entered here
are wrong/invalid, and the XML files being merged have multiple fields that are 
being repeated then, all those fields will have same values in merged NEW XML file 
even though they had different values in OLD XML file. So, to avoid this problem
Same_Field_List array should be filled properly if your XML file consists of 
multiple fields with same name. */

/* Simple XML Elements */
$Old_XML_SXE = new SimpleXMLElement($Old_XML, null, true);
$New_XML_SXE = new SimpleXMLElement($New_XML, null, true);

/* Update XML File */
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML(MergeXML($New_XML_SXE, $Old_XML_SXE, $i));
$dom->save($Tmp_XML);

if ($Schema_XSD != "") {
	if (SchemaValidate($Tmp_XML, $Schema_XSD)) {
		copy($Tmp_XML, $Merged_XML) or exit("ERROR: Failed to rename the generated new XML file.\n");
		unlink($Tmp_XML);
		return 0;
	} else {
		exit("ERROR: The generated new XML file failed the schema validation check.\n");
	}
} else {
	copy($Tmp_XML, $Merged_XML) or exit("ERROR: Failed to rename the generated new XML file.\n");
	unlink($Tmp_XML);
	return 0;
}

/* Function to vaildate schema XSD of a XML file */
function SchemaValidate($xml_file, $xsd_file)
{
	$xml = new DOMDocument();
	$xml->load($xml_file);

	if (!$xml->schemaValidate($xsd_file)) {
		return 0;
	} else {
		return 1;
	}
}

/* Function to constructively merge new XML file with values from old XML file */
function MergeXML(SimpleXMLElement $Old_xml, SimpleXMLElement $New_xml, &$i)
{
	global $Same_Field_List;
	if (count($Old_xml->children()) < 1) {
		//echo " DEBUG: $i : Value of " . $New_xml->getName() . ": $New_xml && " . $Old_xml->getName() .": $Old_xml <br>\n";
		if ($New_xml != "" && $New_xml != $Old_xml) {
			//echo "DEBUG: Updating " . $Old_xml->getName() . " with $New_xml <br>\n";
			$Old_xml = $New_xml;
		}
		$child_att = "";
		foreach ($Old_xml->attributes() as $a => $b) {
			$child_att .= " " . $a . '="' . $b . '"';
		}
		return "<" . $Old_xml->getName() . $child_att . ">" . $Old_xml . "</" . $Old_xml->getName() . ">\n";
	}

	$parnt_att = "";
	foreach ($Old_xml->attributes() as $a => $b) {
		$parnt_att .= " " . $a . '="' . $b . '"';
	}
	$ret = "<" . $Old_xml->getName() . $parnt_att . ">\n";
	$parent_name = $Old_xml->getName();
	foreach ($Old_xml->children() as $key => $child)
	{
		/* echo "DEBUG: Parent is : $parent_name && Key is : $key && Child is : $child <br>\n";
		   echo "DEBUG: New [$i] Parent is : ".$New_xml[0]->getName()." && Key is : ".$New_xml[0]->$key->getName()." && Child is : ".$New_xml[0]->$key." <br>\n";
		   if ($New_xml->$key->getName() != $key) {
		   echo "DEBUG: New Field Found !!! <br>\n";
		   continue;
		   } */

		/* Check if there are any fields which have same Parent/Field Name? If they have same name then 
		   they needs to be updated in a different manner. */
		if (count($Same_Field_List) > 0) {
			for ($m = 0; $m < count($Same_Field_List); $m++) {
				if ($key == $Same_Field_List[$m]) {
					//echo "DEBUG: New [$i] Parent is : ".$New_xml->getName()." && Key is : ".$New_xml->$key->getName()." && Child is : ".$New_xml->{$Same_Field_List[$m]}[$i]." <br>\n";
					$ret .= MergeXML($child , $New_xml->{$Same_Field_List[$m]}[$i], $i);
					if ($m == (count($Same_Field_List) - 1))
						$i++;
					$is_same = "yes";
					break;
				} else {
					$is_same = "no";
				}
			}
		} else {
			$is_same = "no";
		}

		/* Check if a field is available in Old XML file. If that field is
		 * not available then there is no need to update new field values with
		 * old ones and so we will use new fields and its value and skip old
		 * xml file for that field. */
		if ($is_same == "no") {
			if ($New_xml->$key != "") {
				$ret .= MergeXML($child, $New_xml->$key, $i);
			}
			else {
				$ret .= ListXML($child);
			}
		}
	}

	$ret .= "</" . $Old_xml->getName() . ">\n";
	return $ret;
}

/* A recursive function to list XML file values */
function ListXML(SimpleXMLElement $Old_xml)
{
	if (count($Old_xml->children()) < 1) {
		$child_att = "";
		foreach ($Old_xml->attributes() as $a => $b) {
			$child_att .= " " . $a . '="' . $b . '"';
		}
		return "<" . $Old_xml->getName() . $child_att . ">" . $Old_xml . "</" . $Old_xml->getName() . ">\n";
	}

	$parnt_att = "";
	foreach ($Old_xml->attributes() as $a => $b) {
		$parnt_att .= " " . $a . '="' . $b . '"';
	}
	$ret = "<" . $Old_xml->getName() . $parnt_att . ">\n";
	$parent_name = $Old_xml->getName();
	foreach ( $Old_xml->children() as $key => $child )
	{
		$ret .= ListXML($child);
	}

	$ret .= "</" . $Old_xml->getName() . ">\n";
	return $ret;
}

?>
