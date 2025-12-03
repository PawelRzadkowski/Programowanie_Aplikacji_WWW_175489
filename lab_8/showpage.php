<?php

function PokazPodstrone($id)
{
    
    global $conn;

    // Zabezpieczenie ID
    $id_clear = intval($id);
    $query = "SELECT * FROM page_list WHERE id = '$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if (!$result || mysqli_num_rows($result) == 0) {
        return "[nie_znaleziono_strony]";
    }
    $row = mysqli_fetch_assoc($result);

    return $row['page_content'];
}
?>