/*
    cyrb53 (c) 2018 bryc (github.com/bryc)
    License: Public domain (or MIT if needed). Attribution appreciated.
    A fast and simple 53-bit string hash function with decent collision resistance.
    Largely inspired by MurmurHash2/3, but with a focus on speed/simplicity.
*/
const cyrb53 = function(str, seed = 0) {
    let h1 = 0xdeadbeef ^ seed, h2 = 0x41c6ce57 ^ seed;
    for(let i = 0, ch; i < str.length; i++) {
        ch = str.charCodeAt(i);
        h1 = Math.imul(h1 ^ ch, 2654435761);
        h2 = Math.imul(h2 ^ ch, 1597334677);
    }
    h1  = Math.imul(h1 ^ (h1 >>> 16), 2246822507);
    h1 ^= Math.imul(h2 ^ (h2 >>> 13), 3266489909);
    h2  = Math.imul(h2 ^ (h2 >>> 16), 2246822507);
    h2 ^= Math.imul(h1 ^ (h1 >>> 13), 3266489909);
    return 4294967296 * (2097151 & h2) + (h1 >>> 0);
};

export default class extends HTMLElement {
    hashes = {}

    constructor() {
        super();
        this.render();
    }

    async render() {
        const language = this.getAttribute('lang') || 'txt';
        const code = this.innerHTML;

        const config = await this.config();

        if (config['cache-on-client']) {
            const cached = this.cached(language, code);

            if (cached) {
                this.innerHTML = '';
                this.appendChild(this.wrap(cached));
                return Promise.resolve();
            }
        }

        return this.build(language, code);
    }

    async build(language, code) {
        this.style.visibility = 'hidden';

        const response = await axios.post('/__tempest/w-code/highlight', {
            language,
            code,
        });

        this.cache(language, code, response.data);

        this.innerHTML = '';
        this.appendChild(this.wrap(response.data));

        this.style.visibility = 'visible';
    }

    async config() {
        const stored = localStorage.getItem('tempest.w-code.config');

        if (!stored) {
            const response = await axios.get('/__tempest/w-code/config');
            localStorage.setItem('tempest.w-code.config', JSON.stringify(response.data));
            return response.data
        }

        return JSON.parse(stored);
    }

    cached(language, code) {
        return localStorage.getItem(this.hash(language, code));
    }

    cache(language, code, highlighted) {
        localStorage.setItem(this.hash(language, code), highlighted);
    }

    hash(language, code) {
        const key = `${language}${code}`;

        if (!this.hashes[key]) {
            this.hashes[key] = 'tempest.w-code.highlight.' + cyrb53(key);
        }

        return this.hashes[key];
    }

    wrap(highlighted) {
        const code = document.createElement('code');
        code.innerHTML = highlighted;

        const pre = document.createElement('pre');
        pre.classList.add('tempest-w-code-code-wrapper');
        pre.appendChild(code);

        return pre;
    }
}
