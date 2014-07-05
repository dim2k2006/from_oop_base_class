<?php

  // Simple Paginator class
  //
  // (c) 2006 Denis St-Michel - blog.dstmichel.ca

  // Create a unordered list of links for browsing through a set of results


  class Pagination {

    var $pagination;          // the html content of the generated pagination list 
    var $pages;               // the total amount of pages
    var $captions = array();  // associatives array containing captions

    
		// constructor
    function Pagination() {
		  $this->reset();
    }

    
		// reset and clear
    function reset() {
      $this->pagination = '';
      $this->pages      = 0;
			// defining default value for captions
      $this->captions = array('first'=>'&lt;&lt;', 'previous'=>'&lt;', 'next'=>'&gt;', 'last'=>'&gt;&gt;');
       
    }
    

    // setting captions
    function setCaption_first($caption) {
      $this->captions['first'] = $caption;
    }
    function setCaption_previous($caption) {
      $this->captions['previous'] = $caption;
    }
    function setCaption_next($caption) {
      $this->captions['next'] = $caption;
    }
    function setCaption_last($caption) {
      $this->captions['last'] = $caption;
    }


    // calculating the total number of pages
    function setNumberOfPages($total_items,$items_per_page) {
      $this->pages = intval($total_items/$items_per_page)+1;
    }


    // generating the paginator unordered list of links
    function draw($current_page,$url,$size) {
		//$size = $this->pageSizeContent;
     
      // no need to draw a paginator if there is only one page of result
      if ($this->pages != 1) {
 
        // initializing variables;
        $previous_page = $current_page-1;
        $next_page     = $current_page+1;

        if ($previous_page < 1) { $previous_page=1; }
        if ($next_page > $this->pages) { $next_page=$this->pages; }

        // we begin the unordered list of items 
        $this->pagination = '<ul class="pagination">';

        // we start by adding the link for the very first page and the previous page
        if ($current_page != 1) {
          $this->pagination .= '  <li><a href="'.$url.'?page=1&size='.$size.'">'.$this->captions['first'].'</a></li>';
          $this->pagination .= '  <li><a href="'.$url.'?page='.$previous_page.'&size='.$size.'">'.$this->captions['previous'].'</a></li>';
        }
      
        // then we generate a link for every single page
        for ($i=1; $i<=$this->pages; $i++) {
          if ($current_page == $i) {
            $this->pagination .= '<li class="current">'.$i.'</li>';
          }
          else {
            $this->pagination .= '  <li><a href="'.$url.'?page='.$i.'&size='.$size.'">'.$i.'</a></li>';	
          }
        } 

        // we now add the link for the next page and the last page
        if ($current_page != $this->pages) {
          $this->pagination .= '  <li><a href="'.$url.'?page='.$next_page.'&size='.$size.'">'.$this->captions['next'].'</a></li>';
          $this->pagination .= '  <li><a href="'.$url.'?page='.$this->pages.'&size='.$size.'">'.$this->captions['last'].'</a></li>';
        }

        // finally we close the unordered list of links
        $this->pagination .= '</ul>';

      }

    }


  // end of class
  }

?>
