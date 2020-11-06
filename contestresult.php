<?php 
/*
  Plugin Name: Contest Result
  Version: 1.0
  Description: Display all submission entry for each category from WP Contest Plugin
  Author: Liondy
*/

/**
  * [display_submission] returns all submission with all category
  * [display_submission cat=2] returns all asubmission with category 2
  * @return table submission
*/

add_shortcode( 'display_submission', 'display_all_submission' );

function start_result(){
  function display_all_submission($atts) {
    $value = shortcode_atts( array(
      'cat' => 0,
    ), $atts, '' ) ;
    
    $category = $value['cat'];
    switch ($category){
        case 1:
            $category = 'Physics';
            break;
        case 2:
            $category = 'Mathematics';
            break;
        case 3:
            $category = 'Computer';
            break;
        case 4:
            $category = 'Life Science';
            break;
        case 5:
            $category = 'Environmental Science';
            break;
        default:
            $category = 'Physics';
            break;
    }

    global $wpdb;
    
    // $all_submissions = $wpdb->get_results("SELECT * FROM submission WHERE Category = '$category'");
    
    $all_submissions = $wpdb->get_results("SELECT * FROM submission WHERE Category = '$category' ORDER BY Medal ASC");
    
    $total = $wpdb->get_var("SELECT COUNT(*) FROM submission WHERE Category = '$category'");
    
    // var_dump($all_submissions);
    
    
    
    // $submission = $all_submissions;
    // echo $submission;

    $output = '<div class="table-responsive double-scroll">';
    $output .= '<table class="table table-hover">';
    $output .= '<thead>';
    $output .= '<tr>';
    $output .= '<th scope="col">No.</th>';
    $output .= '<th scope="col">Award</th>';
    $output .= '<th scope="col">Country</th>';
    $output .= '<th scope="col">Title</th>';
    $output .= '<th scope="col" width="150px">Abstract</th>';
    $output .= '<th scope="col" width="150px">Poster</th>';
    $output .= '<th scope="col" width="150px">Presentation</th>';
    $output .= '<th scope="col">Student</th>';
    $output .= '<th scope="col">School</th>';
    $output .= '<th scope="col">Supervisor</th>';
    $output .= '</tr>';
    $output .= '</thead>';
    $output .= '<tbody">';
    $output .= '<tr>';
    $i = 1;
    foreach($all_submissions as $submission){
        $country = substr_replace($submission->Country ,"", -2);
        $title = $submission->Title;
        $abstract = $submission->Abstract;
        $poster = $submission->Poster;
        $presentation = $submission->Presentation;
        
        if($abstract[0] != 'h'){
            $abstract = pop_upload_dir_url().$abstract;
        }
        if($poster[0] != 'h'){
            $poster = pop_upload_dir_url().$poster;
        }
        if($presentation[0] != 'h'){
            $presentation = pop_upload_dir_url().$presentation;
        }
        
        $student1 = $submission->Student1;
        $student2 = $submission->Student2;
        $student3 = $submission->Student3;
        $student4 = $submission->Student4;
        
        $student = $student1;
        if($student2 != ''){
            $student .= ', '.$student2;
        }
        else if($student3 != ''){
            $student .= ', '.$student3;
        }
        else if($student4 != ''){
            $student .= ', '.$student4;
        }
        
        $school = $submission->School;
        $supervisor1 = $submission->Supervisor1;
        $supervisor2 = $submission->Supervisor2;
        
        $supervisor = $supervisor1;
        if($supervisor2 != ''){
            $supervisor .= ', '.$supervisor2;
        }
        
        $award = $submission->Medal;
        $medal = '';
        if($award == 1){
            $medal = pop_upload_dir_url().'gold';
        }
        else if($award == 2){
            $medal = pop_upload_dir_url().'silver';
        }
        else if($award == 3){
            $medal = pop_upload_dir_url().'bronze';
        }
        
        $output .= '<th scope="row">'.$i++.'</th>';
        
        if($award == 4){
            $output .= '<td><b>SPECIAL AWARD</b></td>';
        }
        else if($medal == ''){
            $output .= '<td></td>';
        }
        else{
            $output .= '<td><img style="width:100%;" src="'.$medal.'.jpg"></td>';
        }
        
        $output .= '<td>'.$country.'</td>';
        $output .= '<td>'.$title.'</td>';
        $output .= '<td style="width: 150px"><a style="display:block; width=150px" class="pop_external" target="_blank" href="'.$abstract.'">
									<img style="width:100%;" class="pop_external_placeholder" src="'.PC_URI.'images/external.png" alt="" />
								</a></td>';
		$output .= '<td style="width: 150px"><a style="display:block; width=150px" class="pop_external" target="_blank" href="'.$poster.'">
									<img style="width:100%;" class="pop_external_placeholder" src="'.PC_URI.'images/external.png" alt="" />
								</a></td>';
		$output .= '<td style="width: 150px"><a style="display:block; width=150px" class="pop_external" target="_blank" href="'.$presentation.'">
									<img style="width:100%;" class="pop_external_placeholder" src="'.PC_URI.'images/external.png" alt="" />
								</a></td>';
        $output .= '<td>'.$student.'</td>';
        $output .= '<td>'.$school.'</td>';
        $output .= '<td>'.$supervisor.'</td>';
        $output .= '</tr>';
    }
    $output .= '</tbody>';
    $output .= '</table>';
    $output .= '</div>';
    return $output;
  }
}

add_action('init', 'start_result');


?>