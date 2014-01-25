<?php
    
    /* Generates form inputs with label
     * @return  string
     */
    function input($name, $label, $prepend = false, $value = false, $help = false, $type = 'text', $class = false, $disabled = false)
    {
        return '
        <div class="control-group">
            <label class="control-label" for="'. $name .'">'. $label .'</label>
            <div class="controls">
            	'. ($prepend ? '<div class="input-prepend"><span class="add-on">'. $prepend .'</span>' : '') .'
                <input type="'. $type .'" size="46" name="'. $name .'" id="'. $name .'" class="large'. ($class ? ' '. $class : '') .'" value="'. ($value ? (is_object($value) ? $value->$name : $value) : '') .'"'. ($disabled ? ' disabled="disabled"' : '') .'>
                '. ($prepend ? '</div>' : '') .'
                '. ($help ? '<span class="help-block">'. $help .'</span>' : '') .'
            </div>
        </div>';
    }
    
    // ------------------------------------------------------------------------   
    /* Generates form textarea with label
     *
     * @return  string
     */
    function textarea($name, $label, $value = false, $help = false, $limit = false, $class = false)
    {
        return '
        <div class="control-group">
            <label class="control-label" for="'. $name .'">'. $label .'</label>
            <div class="controls">
                <textarea'. ($class ? ' class="'. $class .'"': '') .' '. ($limit ? 'maxlength="'. $limit .'" ' : '') .'name="'. $name .'" id="'. $name .'">'. ($value ? (is_object($value) ? $value->$name : $value) : '') .'</textarea>
                '. ($help ? '<span class="help-block">'. $help .'</span>' : '') .'
            </div>
        </div>';
    }
    
    // ------------------------------------------------------------------------   
    /* Génère un champ de type FileUpload
     * @return  string
     */
    function input_file($name, $label, $help = false, $class = false)
    {
        return '
        <div class="control-group">
            <label class="control-label" for="'. $name .'">'. $label .'</label>
            <div class="controls">
                <input type="file" name="'. $name .'" />
                '. ($help ? '<span class="help-block">'. $help .'</span>' : '') .'
            </div>
        </div>';
    }    
    
    // ------------------------------------------------------------------------
    /* Generates button view
     *
     * @return  string
     */
    function button($link, $label, $icon, $class = false, $id = false, $title = false)
    {
        return '
        <a'. ($id ? ' id="'. $id .'"' : '') .' class="btn'. ($class ? ' '.$class : '') . ($title ? ' tip' : '') .'"'. ($link ? ' href="'. base_url_admin($link) .'"' : '') . ($title ? ' title="'. $title .'"' : '') .'>
            '. ($icon ? '<i class="icon-'. $icon .'"></i> ' : '' ) . $label .'
        </a>';
    }    
    
    // ------------------------------------------------------------------------   
    /* Generates form select with label
     * @return  string
     */
    function select($name, $label, $value = false, $options = array(), $help = false, $class = false)
    {
        $opt = '';
        
        $value = ($value ? (is_object($value) ? $value->$name : $value) : '');      
        
		if (is_string($options))
		{
			$opt = $options;
		}
		else 
		{
	        foreach ($options as $k => $v) 
	        {
	            $opt .= '<option value="'. (isset($k) ? $k : $v) .'"'. ($value == $k ? ' selected="selected"' : '') .'>'. $v .'</option>';   
	        }
		}
        
        return '
        <div class="control-group">
            <label class="control-label" for="'. $name .'">'. $label .'</label>
            <div class="controls">
                <select '. ($class ? ' class="'. $class.'"' : '') .' name="'. $name .'" id="'. $name .'">'. $opt .'</select>
                '. ($help ? '<span class="help-block">'. $help .'</span>' : '') .'
            </div>
        </div>';
    }
    
    // ------------------------------------------------------------------------
    /* Generates form radio with label
     * @return  string
     */
    function radio($name, $label, $value = false, $help = false, $options = array(), $inline = FALSE)
    {
        $value = ($value ? (is_object($value) ? $value->$name : $value) : '');      
        
        $opt = '';
        foreach ( $options as $k => $v ) 
		{
            $opt .=  '<label for="'. $name .'_'. $k .'" class="radio'. ($inline ? ' inline' : ' not-inline') .'">
						<input type="radio" value="'. $k .'" id="'. $name .'_'. $k .'" name="'. $name .'"'. ($value == $k ? ' checked="checked"' : '') .' />
                    '. $v .'</label>';    
        }
        
        return '
        <div class="control-group">
            <label class="control-label" for="'. $name .'">'. $label .'</label>
            <div class="controls">
                '. $opt .'
                '. ($help ? '<p class="help-block">'. $help .'</p>' : '') .'
            </div>
        </div>';
    }
    
    
    // ------------------------------------------------------------------------    
    /* Generates form checkbox with label
     * @return  string
     */
    function checkbox($name, $label, $value = false, $help = false, $options = array(), $inline = false)
    {
        $opt = '';
        $i = 0;
        
        $value = ($value ? (is_object($value) ? $value->$name : $value) : '');      
        
        if ($value) 
		{
            $value_array = explode('-', $value);
        }
        
        foreach ( $options as $k => $v ) 
		{
            $opt .=  '
                <label for="'. $name .'_'. $i .'" class="checkbox'. ($inline ? ' inline' : ' not-inline') .'">
                    <input type="checkbox" value="'. $k .'" name="'. $name .'[]" id="'. $name .'_'. $i .'"'. ($value ? (in_array($k, $value_array) ? ' checked="checked"' : '') : '') .' />
                    '. $v .'
                </label>';    
            $i++;
        }
        
        return '
        <div class="control-group">
            <label class="control-label" for="'. $name .'">'. $label .'</label>
            <div class="controls">
                '. $opt .'
                '. ($help ? '<p class="help-block">'. $help .'</p>' : '') .'
            </div>
        </div>';
    }
    
    // ------------------------------------------------------------------------

    function display_val($value)
    {
		return isset($value) ? $value : ' - ';
    }
    
    // ------------------------------------------------------------------------

    function display_tr($label, $data)
    {
        echo   '<tr>
                    <td class="strong" width="200">'. $label .'</td>
                    <td>'. (strlen($data) ? $data : '<i>Non renseigné</i>') .'</td>
                </tr>';
    }
    
    // ------------------------------------------------------------------------
    
    function table($data)
    {
        $output = '';
        
        if (count($data)) {
            
            $output .= '<table class="table-simple">';
            
            foreach ($data as $k => $v) {
            
                $output .= '<tr><td width="300"><strong>'. $k .'</strong></td><td>'. (strlen($v) ? $v : '<i>Non renseigné</i>') .'</td></tr>';
            }
            
            $output .= '</table>';
        }

        return $output;
    }    
    
    // ------------------------------------------------------------------------
    
    function serialize_form($post)
    {
        foreach ($post as $k => $v) {
            
            if (is_array($v)) {
                $post[$k] = implode('-', $v);
            }
        }
        
        return $post;
    }
    
    // ------------------------------------------------------------------------
    
    function get_helper_value($value, $function) 
    {
        $array = call_user_func($function);
        
        return $array[ $value ];        
    }   
    
    // ------------------------------------------------------------------------
    
    function get_helper_value_list($values, $function) 
    {
        if (strlen($values)) {
            $array = call_user_func($function);
            $output = '';
            $values = explode('-', $values);
            
            foreach ($values as $value) {            
                $output .= $array[ $value ] . ' - ';
            }
            
            return substr($output, 0, -3);
        } else {
            return '-';
        }
    }   
