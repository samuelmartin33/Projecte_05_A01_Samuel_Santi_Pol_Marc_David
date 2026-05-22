const fs = require('fs');
const files = fs.readdirSync('public/js').filter(f => f.endsWith('.js'));
let report = '# Short Variables Report\n\n';

for (const file of files) {
  const content = fs.readFileSync('public/js/' + file, 'utf8');
  let hasShort = false;
  let fileReport = '## ' + file + '\n';
  
  const lines = content.split('\n');
  lines.forEach((line, index) => {
    // Check for short variables (excluding variables 'i', 'j' commonly used for loops, unless requested? The user asked to remove them. But 'i' in a for loop is standard. Let's include everything 1-3 chars).
    const varMatches = line.match(/\b(?:var|let|const)\s+([a-zA-Z0-9_]{1,3})\b\s*=/g);
    // Check for short arguments in anonymous functions
    const funcMatches = line.match(/function\s*\([^)]*\b([a-zA-Z0-9_]{1,3})\b[^)]*\)/g);
    
    if (varMatches || funcMatches) {
       hasShort = true;
       fileReport += `- Line ${index + 1}: ${line.trim()}\n`;
    }
  });
  
  if (hasShort) {
    report += fileReport + '\n';
  }
}
fs.writeFileSync('short_variables_report.md', report);
