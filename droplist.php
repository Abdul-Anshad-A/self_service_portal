<?php
function parseNodes($nodes) {
        $ul = "<ul>\n";
        foreach ($nodes as $node) {
                $ul .= parseNode($node);
        }
        $ul .= "</ul>\n";
        return $ul;
}

function parseNode($node) {
        $li = "\t<li>";
        $li .= '<a href="'.$node->url.'">'.$node->title.'</a>';
        if (isset($node->nodes)) $li .= parseNodes($node->nodes);
        $li .= "</li>\n";
        return $li;
}


$json = '[{
"title":"About",
"url":"/about",
"nodes":[
    {"title":"Staff","url":"/about/staff"},
    {"title":"Location","url":"/about/location"}
]},{
"title":"Contact",
"url":"/contact"
}]';
$nodes = json_decode($json);

$html = parseNodes($nodes);
echo $html;
