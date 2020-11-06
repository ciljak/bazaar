<!-- ******************************************************************* -->
<!-- PHP "self" code handling homepage of bazaar                         -->
<!-- ******************************************************************* -->
<!-- Vrsion: 1.0        Date: 6. - 6.11.2020 by CDesigner.eu             -->
<!-- ******************************************************************* -->
<?php
function generate_page_links($user_search, $sort, $cur_page, $num_pages) {
    $page_links = "";
    echo "<br>";
   
    // if this is not first in row, we need generate the "previous" link
    if  ($cur_page > 1) {
        $page_links .= '<a id="pagination" href="' . $_SERVER['PHP_SELF'] . '?usersearch='
        .$user_search . '&sort=' . $sort . '&page=' . ($cur_page - 1) . '"><img src="./images/previous_icon.png" alt="previous image" width="30" height="30"></a>';

    } else {
        $page_links .= '<span id="pagination"><img src="./images/previous_icon.png" alt="previous image" width="30" height="30"></span> ';
    }

    // Loop through the pages generating the page numbered links
    for($i = 1; $i <= $num_pages; $i++) {
        if  ($cur_page == $i) {  
        $page_links .= '<span id="pagination">' . $i. '</span>'; // span inline element mark non a tag (unlinked number) as pagination for further formating by css
        } else {
        $page_links .= '<a id="pagination" href="' . $_SERVER['PHP_SELF'] . '?usersearch='
        .$user_search . '&sort=' . $sort . '&page=' . $i . '">' . $i . '</a>';
        }
    } 

    // If this page is not last in row, generate "next" link
    if  ($cur_page < $num_pages) {
        $page_links .= '<a id="pagination" href="' . $_SERVER['PHP_SELF'] . '?usersearch='
        .$user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '"><img src="./images/next_icon.png" alt="next image" width="30" height="30"></a>';

    } else {
        $page_links .= ' <span id="pagination"><img src="./images/next_icon.png" alt="next image" width="30" height="30"></span>';
    }
  

    return $page_links;

}





?>