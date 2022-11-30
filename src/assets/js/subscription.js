class SubscriptionJS {
    constructor(){
        this.creator_id = document.querySelector('#creator-id').value;
    }

    Subscribe(){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', "http://localhost:8008/process/id_backend.php", true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let parsed = JSON.parse(xhr.responseText).result;
                var subscriber_id = parsed[0].user_id;

                var payload = {
                    'arg0':this.creator_id,
                    'arg1':subscriber_id
                }

                var args = json2xml(payload, {html : true})
                
                var request = 
                `<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                        <ns1:"subscribe" xmlns:ns1="http://service.tubes2.com/">
                            ${args}
                        </ns1:"subscribe">
                    </soap:Body>
                </soap:Envelope>`;

                let xhr2 = new XMLHttpRequest();
                xhr2.open('POST', "http://tubes2-soap-ws:2434/subscription", true);
                xhr2.setRequestHeader('Content-Type', 'text/xml;charset=UTF-8');
                //xhr2.setRequestHeader('Content-Length', request.length);
                xhr2.setRequestHeader('Authorization', "Ima-Suki-Ni-Naru");
                xhr2.onreadystatechange = () => {
                    if (xhr2.readyState == 4 && xhr2.status == 200) {
                        let parsed2 = JSON.parse(xhr2.responseText);
                        console.log(parsed2)
                    }
                }

                xhr2.send(request);
            }
        }
        xhr.send(null);
    }

    run(){
        this.Subscribe();
    }
}

const subscriptionJS = new SubscriptionJS();
subscriptionJS.run()