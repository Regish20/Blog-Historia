<?php
function parseMarkdown($text) {
    // Convertir líneas a arrays
    $lines = explode("\n", $text);
    $html = '';
    $inBlockquote = false;
    
    foreach ($lines as $line) {
        $trimmed = trim($line);
        
        // Línea horizontal
        if (preg_match('/^---+$/', $line)) {
            if ($inBlockquote) {
                $html .= "</blockquote>\n";
                $inBlockquote = false;
            }
            $html .= "<hr>\n";
            continue;
        }
        
        // Cita en bloque
        if (preg_match('/^>\s?(.*)$/', $line, $matches)) {
            if (!$inBlockquote) {
                $html .= "<blockquote>";
                $inBlockquote = true;
            }
            $html .= parseInlineMarkdown($matches[1]) . "<br>";
            continue;
        } else if ($inBlockquote) {
            $html .= "</blockquote>\n";
            $inBlockquote = false;
        }
        
        // Encabezado H1 #
        if (preg_match('/^#\s+(.*)$/', $line, $matches)) {
            $html .= "<h4>" . parseInlineMarkdown($matches[1]) . "</h2>\n";
            continue;
        }
        
        // Encabezado H2 ##
        if (preg_match('/^##\s+(.*)$/', $line, $matches)) {
            $html .= "<h5>" . parseInlineMarkdown($matches[1]) . "</h3>\n";
            continue;
        }
        
        // Encabezado H3 ###
        if (preg_match('/^###\s+(.*)$/', $line, $matches)) {
            $html .= "<h6>" . parseInlineMarkdown($matches[1]) . "</h4>\n";
            continue;
        }
        
        // Lista no ordenada
        if (preg_match('/^[\*\-\+]\s+(.*)$/', $line, $matches)) {
            if (!empty($trimmed)) {
                $html .= "<ul><li>" . parseInlineMarkdown($matches[1]) . "</li></ul>\n";
            }
            continue;
        }
        
        // Lista ordenada
        if (preg_match('/^\d+\.\s+(.*)$/', $line, $matches)) {
            if (!empty($trimmed)) {
                $html .= "<ol><li>" . parseInlineMarkdown($matches[1]) . "</li></ol>\n";
            }
            continue;
        }
        
        // Línea vacía
        if (empty($trimmed)) {
            $html .= "<br>\n";
            continue;
        }
        
        // Párrafo normal
        $html .= "<p>" . parseInlineMarkdown($line) . "</p>\n";
    }
    
    // Cerrar cualquier cita abierta
    if ($inBlockquote) {
        $html .= "</blockquote>\n";
    }
    
    // Limpiar dobles breaks y listas múltiples
    $html = preg_replace('/(<br>\s*)+/', '<br>', $html);
    $html = preg_replace('/<\/ul>\s*<br>\s*<ul>/s', '', $html);
    $html = preg_replace('/<\/ol>\s*<br>\s*<ol>/s', '', $html);
    
    return $html;
}

function parseInlineMarkdown($text) {
    // Negrita **texto**
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    
    // Cursiva *texto*
    $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
    
    // Links [texto](url)
    $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank">$1</a>', $text);
    
    // Código `codigo`
    $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
    
    return $text;
}
?>