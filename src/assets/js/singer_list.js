class SingerListJS {
    constructor() {
        this.next_btn = document.querySelector('.l-next-page-icon');
        this.prev_btn = document.querySelector('.l-previous-page-icon');
        this.output = document.querySelector('.alb-middle-limit');
        this.page = 1;
        this.limit = 5;
        this.pagecount = document.querySelector('.l-current-page');
    }

    fetchCurrentPage() {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://localhost:3001/api/getUsers', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = () => {
            if (xhr.status === 200 && xhr.readyState === 4) {
                let parsed = JSON.parse(xhr.responseText);
                /*
                if (parsed.length === 0) {
                    this.previousPage();
                    this.stop_page = true;   
                }*/
                this.output.innerHTML = '';
                let xhr2 = new XMLHttpRequest();
                xhr2.open('GET', 'http://localhost:8008/components/list-display.php' + "?type=singer&json=" +  JSON.stringify(parsed), true);
                xhr2.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr2.onreadystatechange = () => {
                    if (xhr2.status === 200 && xhr2.readyState === 4) {
                        this.output.innerHTML = xhr2.responseText;
                    }
                }
                xhr2.send(null);
            }
        }
        xhr.send(null);
    }

    previousPage() {
        if (this.page > 1) {
            this.page--;
            this.fetchCurrentPage();
            this.pagecount.innerHTML = this.page;
        }
    }
    nextPage() {
        if (!this.stop_page) {
            this.page++;
            this.fetchCurrentPage();
            this.pagecount.innerHTML = this.page;
        }
    }
    run() {
        this.fetchCurrentPage();
        this.next_btn.addEventListener('click', () => {
            this.nextPage();
        });
        this.prev_btn.addEventListener('click', () => {
            this.previousPage();
        });
    }
}

const singerListJS = new SingerListJS();
singerListJS.run();