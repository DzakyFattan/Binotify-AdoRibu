<?php
    function get_l_d_elmt($type, $json) {
        $json = json_decode($json);
        $retval =  '
            <li>
                <a href=" '. 
                    ($type === "song" ? ('song.php?id=' . $json->song_id) : ( $type === "album" ? ('album.php?id=' . $json->album_id) : '#'))
                    . '
                " class="l-elmt">';
        if ($type === "song" || $type === "album") {
            $retval .= '<div class="l-elmt-img-wrapper">
                        <img src="'. $json->image_path .'" alt="Song List Image" class="s-img-cover">
                    </div>
                    <div class="l-elmt-detail-wrapper">
                        <div class="l-elmt-detail"> 
                                <div class="l-elmt-detail-title">' . $json->judul .'</div>
                            <span>'.($json->penyanyi !== null ? $json->penyanyi : 'Unknown').'</span>
                            <span>' . substr($json->tanggal_terbit, 0, 4) . '</span> 
                            <span>' .  
                                ($json->genre !== null ? $json->genre : 'Unknown')
                            .
                            '</span>
                        </div>';
        } else if ($type === "user") {
            $retval .= '<div class="l-elmt-img-wrapper"><img src="/assets/img/doge.jpg" alt="Song List Image" class="s-img-cover"></div>
                        <div class="l-elmt-detail-wrapper">
                            <div class="l-elmt-detail"> 
                                <div class="l-elmt-detail-title">' . $json->username .'</div>
                            <span>'. $json->email.'</span>
                            <span>' . $json->user_id . '</span> 
                        </div>';
        }  
        $retval .= '</div>
                    <div class="delete-icon-wrap visib-hidden">
                        <img src="/assets/img/trash-o.svg" alt="">
                    </div>
                </a>
            </li>
        ';
        return $retval;
    }
?>