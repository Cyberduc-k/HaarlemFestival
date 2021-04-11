const hash = location.hash.length === 0 ? [] : location.hash.substring(1).split('&');
const pairs = {};

for (const part of hash) {
    if (part.length === 0) continue;
    const pair = part.split('=');

    pairs[pair[0]] = pair[1];
}

function updateHash() {
    let hash = "#";

    for (const key in pairs) {
        if (hash.length !== 1) hash += '&';
        hash += `${key}=${pairs[key]}`;
    }

    location.hash = hash;
}

function getHash(name) {
    return pairs[name];
}

function setHash(name, value) {
    pairs[name] = value;
    updateHash();
}

function unsetHash(name) {
    delete pairs[name];
    updateHash();
}
