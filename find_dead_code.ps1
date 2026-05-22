 = Get-ChildItem -Path "public\js" -Filter "*.js"
 = @{}
foreach ( in ) {
     = Get-Content .FullName
    foreach ( in ) {
        if ( -match "function\s+([a-zA-Z0-9_]+)\s*\(") {
             = $matches[1]
            if (-not $functions.ContainsKey()) {
                $functions[] = $file.Name
            }
        }
    }
}

foreach ( in $functions.Keys) {
     = 0
     = Select-String -Path "public\js\*.js" -Pattern "\b\b"
    if () {  += .Count }
    
     = Get-ChildItem -Path "resources\views" -Recurse -Filter "*.blade.php" | Select-String -Pattern "\b\b"
    if () {  += .Count }
    
    if ( -eq 1) {
        Write-Host " in "
    }
}
