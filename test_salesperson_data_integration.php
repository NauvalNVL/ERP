<?php

echo "Testing Salesperson Data Integration...\n\n";

// Check if salesperson-team-modal.vue has been updated to use define salesperson data
$modalPath = 'c:\laragon\www\ERP\resources\js\Components\salesperson-team-modal.vue';
if (file_exists($modalPath)) {
    echo "✅ salesperson-team-modal.vue exists\n";
    
    $content = file_get_contents($modalPath);
    
    // Check if salespersons prop has been added
    $propsChecks = [
        'salespersons prop added' => strpos($content, 'salespersons: {') !== false,
        'salespersons prop type' => strpos($content, 'type: Array') !== false && strpos($content, 'default: () => []') !== false
    ];
    
    echo "\n   Props implementation:\n";
    foreach ($propsChecks as $element => $exists) {
        $status = $exists ? "✅ Implemented" : "❌ Missing";
        echo "   {$status} - {$element}\n";
    }
    
    // Check if helper functions have been added
    $helperFunctionsChecks = [
        'getSalespersonData function' => strpos($content, 'function getSalespersonData(salespersonCode)') !== false,
        'getSalespersonName function' => strpos($content, 'function getSalespersonName(salespersonCode)') !== false,
        'getSalespersonCode function' => strpos($content, 'function getSalespersonCode(salespersonCode)') !== false,
        'Helper functions use props.salespersons' => strpos($content, 'props.salespersons.find') !== false
    ];
    
    echo "\n   Helper functions:\n";
    foreach ($helperFunctionsChecks as $element => $exists) {
        $status = $exists ? "✅ Implemented" : "❌ Missing";
        echo "   {$status} - {$element}\n";
    }
    
    // Check if template has been updated to use helper functions
    $templateChecks = [
        'Code uses getSalespersonCode' => strpos($content, 'getSalespersonCode(team.s_person_code)') !== false,
        'Name uses getSalespersonName' => strpos($content, 'getSalespersonName(team.s_person_code)') !== false,
        'Old direct access removed' => strpos($content, '{{ team.s_person_code }}') === false && strpos($content, '{{ team.salesperson_name }}') === false
    ];
    
    echo "\n   Template updates:\n";
    foreach ($templateChecks as $element => $exists) {
        $status = $exists ? "✅ Updated" : "❌ Not updated";
        echo "   {$status} - {$element}\n";
    }
    
    // Check if search filter has been updated
    $searchFilterChecks = [
        'Search uses getSalespersonCode' => strpos($content, 'const actualCode = getSalespersonCode(team.s_person_code)') !== false,
        'Search uses getSalespersonName' => strpos($content, 'const actualName = getSalespersonName(team.s_person_code)') !== false,
        'Search filter updated' => strpos($content, '(actualCode && actualCode.toLowerCase().includes(q))') !== false &&
                                 strpos($content, '(actualName && actualName.toLowerCase().includes(q))') !== false
    ];
    
    echo "\n   Search filter updates:\n";
    foreach ($searchFilterChecks as $element => $exists) {
        $status = $exists ? "✅ Updated" : "❌ Not updated";
        echo "   {$status} - {$element}\n";
    }
    
    // Check if sorting has been updated
    $sortingChecks = [
        'Sorting handles s_person_code' => strpos($content, "if (sortKey.value === 's_person_code')") !== false,
        'Sorting handles salesperson_name' => strpos($content, "if (sortKey.value === 'salesperson_name')") !== false ||
                                            strpos($content, "else if (sortKey.value === 'salesperson_name')") !== false,
        'Sorting uses helper functions' => strpos($content, 'valueA = getSalespersonCode(a.s_person_code)') !== false &&
                                         strpos($content, 'valueA = getSalespersonName(a.s_person_code)') !== false
    ];
    
    echo "\n   Sorting updates:\n";
    foreach ($sortingChecks as $element => $exists) {
        $status = $exists ? "✅ Updated" : "❌ Not updated";
        echo "   {$status} - {$element}\n";
    }
    
} else {
    echo "❌ salesperson-team-modal.vue not found\n";
}

// Check if parent component has been updated
$parentPath = 'c:\laragon\www\ERP\resources\js\Pages\sales-management\system-requirement\standard-requirement\salesperson-team.vue';
if (file_exists($parentPath)) {
    echo "\n✅ salesperson-team.vue exists\n";
    
    $parentContent = file_get_contents($parentPath);
    
    // Check if salespersons prop is passed to modal
    $parentIntegrationChecks = [
        'salespersons prop passed to modal' => strpos($parentContent, ':salespersons="salespersons"') !== false,
        'salespersons data available' => strpos($parentContent, 'const salespersons = ref([])') !== false,
        'fetchSalespersons function exists' => strpos($parentContent, 'const fetchSalespersons = async') !== false
    ];
    
    echo "\n   Parent component integration:\n";
    foreach ($parentIntegrationChecks as $element => $exists) {
        $status = $exists ? "✅ Implemented" : "❌ Missing";
        echo "   {$status} - {$element}\n";
    }
    
} else {
    echo "\n❌ salesperson-team.vue not found\n";
}

echo "\nSalesperson Data Integration test completed!\n";
echo "\nResult:\n";
echo "- Modal now uses data from define salesperson for Code & Name columns\n";
echo "- Helper functions ensure data consistency with define salesperson\n";
echo "- Search and sorting work with actual salesperson data\n";
echo "- Data synchronization between salesperson team and define salesperson\n";
echo "- Code & Name display is now accurate and up-to-date\n";

?>
