<?php

function out( $msg, $obj ) {
	if( DEBUG_MODE ) {
	//if( !DEBUG_MODE ) echo '<!--';
	echo '<p>'. $msg .'</p>';
	echo '<pre>';
	print_r( $obj );
	echo '</pre>';
	//if( !DEBUG_MODE ) echo '-->';
	}
}

function parse_xml( $xml ) {
	$xmlObj = simplexml_load_string($xml);
	$arrXml = objectsIntoArray($xmlObj);
	return $arrXml;
}

function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
   
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
   
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}

function dom_to_array($root)
{
    $result = array();

    if ($root->hasAttributes())
    {
        $attrs = $root->attributes;

        foreach ($attrs as $i => $attr)
            $result[$attr->name] = $attr->value;
    }

    $children = $root->childNodes;

    if ($children->length == 1)
    {
        $child = $children->item(0);

        if ($child->nodeType == XML_TEXT_NODE)
        {
            $result['_value'] = $child->nodeValue;

            if (count($result) == 1)
                return $result['_value'];
            else
                return $result;
        }
    }

    $group = array();

    for($i = 0; $i < $children->length; $i++)
    {
        $child = $children->item($i);

        if (!isset($result[$child->nodeName]))
            $result[$child->nodeName] = dom_to_array($child);
        else
        {
            if (!isset($group[$child->nodeName]))
            {
                $tmp = $result[$child->nodeName];
                $result[$child->nodeName] = array($tmp);
                $group[$child->nodeName] = 1;
            }

            $result[$child->nodeName][] = dom_to_array($child);
        }
    }

    return $result;
} 

?>