const fs = require('fs');
const path = require('path');

function getFiles(dir, ext) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(file => {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) {
            results = results.concat(getFiles(file, ext));
        } else if (file.endsWith(ext)) {
            results.push(file);
        }
    });
    return results;
}

const jsFiles = getFiles('public/js', '.js');
const bladeFiles = getFiles('resources/views', '.blade.php');

const declarations = {}; 

const regexFunc = /function\s+([a-zA-Z0-9_]+)\s*\(/g;
const regexVar = /(?:var|let|const)\s+([a-zA-Z0-9_]+)(?:\s*=|;|,)/g;

for (const file of jsFiles) {
    const content = fs.readFileSync(file, 'utf8');
    let match;
    while ((match = regexFunc.exec(content)) !== null) {
        declarations[match[1]] = file;
    }
    while ((match = regexVar.exec(content)) !== null) {
        declarations[match[1]] = file;
    }
}

const ignore = ['onload', 'onpopstate', 'onclick', 'i', 'j', 'k', 'e', 'event', 'console', 'document', 'window', 'math', 'string', 'date', 'setTimeout', 'clearTimeout', 'setInterval', 'clearInterval', 'Array', 'length', 'index', 'item', 'el', 'res', 'msg'];

const deadCode = [];

for (const [name, filePath] of Object.entries(declarations)) {
    if (name.length <= 2 || ignore.includes(name)) continue;
    
    let count = 0;
    const searchRegex = new RegExp(`\\b${name}\\b`, 'g');
    
    for (const file of jsFiles) {
        const content = fs.readFileSync(file, 'utf8');
        const matches = content.match(searchRegex);
        if (matches) count += matches.length;
    }
    
    for (const file of bladeFiles) {
        const content = fs.readFileSync(file, 'utf8');
        const matches = content.match(searchRegex);
        if (matches) count += matches.length;
    }
    
    if (count === 1) {
        deadCode.push({name, filePath});
    }
}

console.log(JSON.stringify(deadCode, null, 2));
