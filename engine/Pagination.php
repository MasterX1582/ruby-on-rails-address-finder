<?php
class Pagination {

    static public $default_limit = 10;
    static private $pagination_html;
    static private $pagination_links = [];

    static protected function assume_page_num_segment() {
        $page_num_segment = 3; //our default assumption

        //are we using a custom route?
        $target_url = current_url();

        foreach (CUSTOM_ROUTES as $key => $value) {
            $pos = strpos($target_url, $key);

            if (is_numeric($pos)) {
                //we must be viewing a custom route!
                $target_url = str_replace($key, $value, $target_url);

                //compare num segments in key (nice URL) and value (assumed URL)
                $key_bits = explode('/', $key);
                $value_bits = explode('/', $value);

                $diff = count($value_bits)-count($key_bits);
                if ($diff != 0) {
                    $page_num_segment = $page_num_segment-$diff;
                }

            }

        }

        return $page_num_segment;
    }

    static public function display($data=NULL) {

        if (!isset($data)) {
            die('<br><b>ERROR:</b> Data must be passed into the pagination class in order for it to work.  Please refer to documentation.');
        } elseif (is_numeric($data)) {
            $total_rows = $data;
            unset($data);
            $data['include_css'] = true;
            $data['total_rows'] = $total_rows;
        }

        if (!isset($data['total_rows'])) {
            die('<br><b>ERROR:</b> The $data[\'total_rows\'] value must be passed into the pagination class in order for it to work.');
        } else {
            $total_rows = $data['total_rows'];
        }

        if (!isset($data['include_css'])) {
            $pagination_data['include_css'] = false;
        } else {
            $pagination_data['include_css'] = $data['include_css'];
        }

        if (!isset($data['num_links_per_page'])) {
            $pagination_data['num_links_per_page'] = 10;
        } else {
            $pagination_data['num_links_per_page'] = $data['num_links_per_page'];
        }

        if (!isset($data['template'])) {
            $pagination_template = 'default';
        } else {
            $pagination_template = $data['template'];
        }

        if (!isset($data['page_num_segment'])) {
            //$page_num_segment = 3;
            $page_num_segment = self::assume_page_num_segment();
        } else {
            $page_num_segment = $data['page_num_segment'];
        }

        if (!isset($data['pagination_root'])) {

            $pagination_root = BASE_URL;
            $segments_data = get_segments(true);
            $segments = $segments_data['segments'];

            if (isset($segments[1])) {
                $pagination_root.= $segments[1];
            } 

            $usefull_segments = array_slice($segments, 2);
            foreach($usefull_segments as $segment) {
				if (isset($segment)) {
					if (!is_numeric($segment)){
						 $pagination_root.= '/'.$segment;
					}
				}
			}

        } else {
            $pagination_root = BASE_URL.$data['pagination_root'];
        }

        $pagination_data['root'] = $pagination_root.'/';

        if (!isset($data['limit'])) {
            $limit = self::$default_limit;
        } else {
            $limit = $data['limit'];
        }

$segments = get_segments(true);
$segments = $segments['segments'];
// foreach ($segments as $key => $value) {
//     echo "key of $key is $value<br>";
// }
// die();
unset($segments[4]);



// var_dump($segments); die();

        $current_page = self::get_page_num($page_num_segment, $segments);
        $num_pages = (int) ceil($total_rows / $limit);

        if ($num_pages<2) {
            return '';
        }

        if (!isset($template)) {
            $template = 'default';
        }

        $target_settings_method = 'get_settings_'.$template;
        $settings = self::$target_settings_method();
        $pagination_data['settings'] = $settings;

        $num_links_per_page = $pagination_data['num_links_per_page'];
        $num_links_to_side = (int) ceil($num_links_per_page/2);
        
        if (($current_page-$num_links_to_side)-1 > 0) {
            $start = $current_page - ($num_links_to_side - 1);
        } else {
            $start = 1;
        }

        if (($current_page+$num_links_to_side)<$num_pages) {
            $end = $current_page + $num_links_to_side;
        } else {
            $end = $num_pages;
        }

        //figure out the prev and next links
        if (($current_page-1)>0) {
            $prev = $current_page-1;
        } else {
            $prev = '';
        }

        if (($current_page+1)>$num_pages) {
            $next = $num_pages;
        } else {
            $next = $current_page+1;
        }

        if (isset($data['include_showing_statement'])) {

            if (isset($data['record_name_plural'])) {
                $record_name_plural = $data['record_name_plural'];
            } else {
                $record_name_plural = NULL;
            }

            $pagination_data['showing_statement'] = self::get_showing_statement($limit, $current_page, $total_rows, $record_name_plural);
        }
        
        $pagination_data['total_rows'] = $total_rows;
        $pagination_data['template'] = $pagination_template;
        $pagination_data['pagination_root'] = $pagination_root;
        $pagination_data['current_page'] = $current_page;
        $pagination_data['page_num_segment'] = $page_num_segment;
        $pagination_data['start'] = $start;
        $pagination_data['end'] = $end;
        $pagination_data['num_links_to_side'] = $num_links_to_side;
        $pagination_data['num_pages'] = $num_pages;
        $pagination_data['prev'] = $prev;
        $pagination_data['next'] = $next;

        self::draw_pagination($pagination_data);        
    }

    static public function get_page_num($page_num_segment, $segments) {
        $page_num = 1;

        if (isset($segments[$page_num_segment])) {
            $segment_value = $segments[$page_num_segment];
            if (is_numeric($segment_value)) {
                $page_num = $segment_value;
            }
        }

        return $page_num;

    }

    static public function draw_pagination($pagination_data) {
        
        extract($pagination_data);
    
        if (isset($showing_statement)) {
            echo '<p>'.$showing_statement.'</p>';
        }

        if ($current_page>1) {
            $links[] = 'first_link';
            $links[] = 'prev_link';
        }

        for ($i=$start; $i <= $end; $i++) { 
            $links[] = $i;
        }

        if ($current_page<$num_pages) {
            $links[] = 'next_link';
            $links[] = 'last_link';
        }

        $nl = '
';

        $html = $nl.$nl.$settings['pagination_open'].$nl;
        foreach ($links as $key => $value) {

            if (is_numeric($value)) {

                if ($value == $current_page) {
                    $html.= $settings['cur_link_open'];
                    $html.= $value;
                    $html.= $settings['cur_link_close'];
                } else {

                    $html.= $settings['num_link_open'];
                    $html.= self::attempt_build_link($value, $pagination_data);
                    $html.= $settings['num_link_close'];
                    $html.= $nl;

                }

            } else {

                $html.= $settings[$value.'_open'];
                $html.= self::attempt_build_link($value, $pagination_data);
                $html.= $settings[$value.'_close'];
                $html.= $nl;
            
            }

        }

        $html.= $settings['pagination_close'];
        $html = str_replace('><', '>'.$nl.'<', $html);

        if ($include_css == true) {
            $html.= self::get_sample_css();
        }
        
        echo $html;
    }

    static public function attempt_build_link($value, $pagination_data) {

        extract($pagination_data);

        switch ($value) {
            case 'first_link':
                $html = '<a href="'.$root.'">'.$settings['first_link'].'</a>';
                break;
            case 'last_link':
                $html = '<a href="'.$root.$num_pages.'">'.$settings['last_link'].'</a>';
                break;
            case 'prev_link':
                $html = '<a href="'.$root.$prev.'">'.$settings['prev_link'].'</a>';
                break;
            case 'next_link':
                $html = '<a href="'.$root.$next.'">'.$settings['next_link'].'</a>';
                break;
            default:
                $html = '<a href="'.$root.$value.'">'.$value.'</a>';
                break;
        }

        return $html;
    }

    static public function get_settings_default() {

        $settings['pagination_open'] = '<div class="pagination">';
        $settings['pagination_close'] = '</div>';

        $settings['cur_link_open'] = '<a href="#" class="active">';
        $settings['cur_link_close'] = '</a>';

        $settings['num_link_open'] = '';
        $settings['num_link_close'] = '';

        $settings['first_link'] = 'First';
        $settings['first_link_open'] = '';
        $settings['first_link_close'] = '';

        $settings['last_link'] = 'Last';
        $settings['last_link_open'] = '';
        $settings['last_link_close'] = '';

        $settings['prev_link'] = '&laquo;';
        $settings['prev_link_open'] = '';
        $settings['prev_link_close'] = '';

        $settings['next_link'] = '&raquo;';
        $settings['next_link_open'] = '';
        $settings['next_link_close'] = '';
        return $settings;
    }

    static public function get_showing_statement($limit, $current_page, $total_rows, $record_name_plural=NULL) {

        $offset = ($current_page * $limit) - $limit;
        
        $value1 = $offset+1;
        $value2 = $offset+$limit;
        $value3 = $total_rows;

        if ($value2>$value3) {
            $value2 = $value3;
        }

        if (!isset($record_name_plural)) {
            $record_name_plural = 'results';
        }

        $showing_statement = "Showing ".$value1." to ".$value2." of ".number_format($value3)." $record_name_plural.";
        return $showing_statement;
    }

    static public function get_sample_css() {
        $css = '
<style>
.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  border: 1px solid #ddd;
}

.pagination a.active {
  background-color: #636ec6;
  color: white;
  border: 1px solid #636ec6;
}

.pagination a:hover:not(.active) {background-color: #ddd;}

.pagination a:first-child {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
}

.pagination a:last-child {
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}
</style>
        ';
        return $css;
    }

}
