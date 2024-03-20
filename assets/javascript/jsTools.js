class ApiGrabber{
    constructor (api_key = "") {
        this.#instanceId = Math.floor(Math.random() * 4294967296).toString(16);
        this.#api_key = (typeof key == "string") ? key : "";
    }

    #send = (method, table, id = undefined, params = "") => {

    var methods = new Array(
        "GET", "POST", "PUT", "DELETE"
    );

    if (!methods.includes(method)) {
        return false;
    }

    var requestUrl = null;
    if (table != null) {
        requestUrl = "http://relaxandsea/api/"+table;
        
        if (typeof id !== 'undefined') {
        requestUrl += "/" + id;
        }
    }
    else {return false;}

    if (typeof params == "object") {
        try {
            params = JSON.stringify(params);
        } catch (error) {
            console.error("Request No."+this.#reqNum+" Failed\nReason: Params could not be converted to JSON format");
            return false;
        }
    }

    return $.ajax({
        url: requestUrl, 
        method: method,
        data: params,
        dataType: "text",
        success: function(result){
            return result;
        }
    });

    }
    
    #instanceId;

    #reqNum = 0;

    #lastResult = {};
    getLastResult = () => {return this.#lastResult;};

    #api_key = "";
    setApiKey = (key) => {this.#api_key = (typeof key == "string") ? key : "";};

    #msgGenerator = () => {
        var no = ++this.#reqNum;
        var msg = "Inst.: "+this.#instanceId+" Request No."+no+"\n";
        return msg;
    }

    create = async (table, params) => {
        var msg = this.#msgGenerator();

        if (typeof table !== "string") {
            console.error(msg+"Table was not a string");
            return Promise.reject(false);
        }
        if(typeof params == "object" && params.constructor == Array) {
            var data = {
                api_key: this.#api_key,
                values: params
            }
        }
        else
            {console.error(msg+"Params was not an array."); return Promise.reject(false);}

        var ret;
        var success;
        await $.when(this.#send("POST",table,undefined,data))
            .then(function(result) {
                const reply = JSON.parse(result);
                msg += reply.action +
                    " in " + reply.table +
                    ((typeof id !== "undefined") ? " at " + reply.record : "" ) + 
                    " => " + reply.status + ";\nStatus => "+reply.code+";";
                    
                    success = true;
                    console.log(msg);
                    ret = reply;

            }, function (reason){
                const reply = JSON.parse(reason.responseText);
                
                msg += reply.action +
                    " in " + reply.table +
                    ((typeof id !== "undefined") ? " at " + reply.record : "" ) + 
                    " => " + reply.status + ";\nReason => "+reply.reason+" "+reply.code+";";

                    success = false;
                    console.error(msg);
                    ret = reply;
            });

        this.#lastResult = ret;
        if (success) {
            return Promise.resolve(ret);
        }
        else {
            return Promise.reject(ret);
        }

        
    }

    read = async (table, id = undefined) => {
        var msg = this.#msgGenerator();
        if (typeof table !== "string") {
            console.error(msg+"Table was not a string");
            return Promise.reject(false);
        }
        if (typeof id !== "number") {
            if (typeof id !== "undefined") {
                console.error(msg+"Id was not a number: "+(typeof id));
                return Promise.reject(false);
            }
        }
    
        var ret;
        var success;
        await $.when(this.#send("GET",table,id))
            .then(function(result) {
                const reply = JSON.parse(result);
                msg += reply.action +
                    " of " + reply.table +
                    ((typeof id !== "undefined") ? " at " + reply.record : "" ) + 
                    " => " + reply.status + ";\nStatus => "+reply.code+";";

                    success = true;
                    console.log(msg);
                    ret = reply;
            }, function (reason){
                const reply = JSON.parse(reason.responseText);
                
                msg += reply.action +
                    " of " + reply.table +
                    ((typeof id !== "undefined") ? " at " + reply.record : "" ) + 
                    " => " + reply.status + ";\nReason => "+reply.reason+" "+reply.code+";";

                success = false;
                console.error(msg);
                ret = reply;
            });
        
        this.#lastResult = ret;
        if (success) {
            return Promise.resolve(ret);
        }
        else {
            return Promise.reject(ret);
        }
    }

    update = async (table, id, params) => {
        var msg = this.#msgGenerator();
        if (typeof table !== "string") {
            console.error(msg+"Table was not a string");
            return Promise.reject(false);
        }
        if(typeof params === "object" && params.constructor === Object) {
            var data = {
                api_key: this.#api_key,
                values: params
            }
        }
        else
            {console.error(msg+"Params was not an object."); return Promise.reject(false);}

        var ret;
        var success;
        await $.when(this.#send("PUT",table,id,data))
            .then(function(result) {
                const reply = JSON.parse(result);
                msg += reply.action +
                    " in " + reply.table +
                    ((typeof id !== "undefined") ? " at " + reply.record : "" ) + 
                    " => " + reply.status + ";\nStatus => "+reply.code+";";
                
                success = true;
                console.log(msg);
                ret = reply;

            }, function (reason){
                const reply = JSON.parse(reason.responseText);
                
                msg += reply.action +
                    " in " + reply.table +
                    ((typeof id !== "undefined") ? " at " + reply.record : "" ) + 
                    " => " + reply.status + ";\nReason => "+reply.reason+" "+reply.code+";";

                success = false;
                console.error(msg);
                ret = reply;
            });

        this.#lastResult = ret;
        if (success) {
            return Promise.resolve(ret);
        }
        else {
            return Promise.reject(ret);
        }
    }

    delete = async (table, id) => {
        var msg = this.#msgGenerator();

        if (typeof table !== "string") {
            console.error(msg+"Table was not a string");
            return Promise.reject(false);;
        }
        if (typeof id !== "number") {
            console.error(msg+"Id was not a number");
            return Promise.reject(false);;
        }
        var data = {
                api_key: this.#api_key,
        };

        var ret;
        var success;
        await $.when(this.#send("DELETE",table,id,data))
            .then(function(result) {
                const reply = JSON.parse(result);
                msg += reply.action +
                    " in " + reply.table +
                    ((typeof id !== "undefined") ? " at " + reply.record : "" ) + 
                    " => " + reply.status + ";\nStatus => "+reply.code+";";
                
                success = true;
                console.log(msg);
                ret = reply;

            }, function (reason){
                const reply = JSON.parse(reason.responseText);
                
                msg += reply.action +
                    " in " + reply.table +
                    ((typeof id !== "undefined") ? " at " + reply.record : "" ) + 
                    " => " + reply.status + ";\nReason => "+reply.reason+" "+reply.code+";";

                success = false;
                console.error(msg);
                ret = reply;
            });

        this.#lastResult = ret;
        if (success) {
            return Promise.resolve(ret);
        }
        else {
            return Promise.reject(ret);
        }
    }

}
