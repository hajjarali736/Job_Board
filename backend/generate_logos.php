<?php
// Create assets directory if it doesn't exist
if (!file_exists('../assets')) {
    mkdir('../assets', 0777, true);
}

// Company logos to generate
$companies = [
    'techcorp' => 'Tech Corp',
    'financeinc' => 'Finance Inc',
    'healthplus' => 'Health Plus',
    'edutech' => 'EduTech'
];

// Generate a logo for each company
foreach ($companies as $key => $name) {
    $image = imagecreatetruecolor(200, 200);
    
    // Set background color (light gray)
    $bg = imagecolorallocate($image, 240, 240, 240);
    imagefill($image, 0, 0, $bg);
    
    // Set text color (dark gray)
    $textColor = imagecolorallocate($image, 100, 100, 100);
    
    // Add company name
    imagettftext($image, 20, 0, 10, 100, $textColor, 'arial.ttf', $name);
    
    // Save the image
    imagepng($image, "../assets/{$key}-logo.png");
    imagedestroy($image);
}

echo "Company logos generated successfully!";
?> 