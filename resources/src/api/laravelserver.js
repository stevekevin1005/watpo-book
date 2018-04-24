

let GETOPTION = () => ({
    method: 'GET'
});

export function sendSMSApi(data) {
    console.log('API getUserApi-->%j', GETOPTION());
    return fetch(`/api/sendSMS?name=${data.name}&phone=${data.number}`, GETOPTION())
        .then((response) => { return response.json() }).then((res) => {
            let returnData = res
            console.log("returnData.status: ", res.status)
            console.log("returnData: ", res)

            switch (returnData.status) {
                case 0:
                    returnData.type = "SMS_IS_SENT"
                    break;
                case 1:
                    returnData.type = "RE_ENTER_CODE"
                    break;
                case 2:
                    returnData.type = "SMS_ERROR"
                    break;
            }


            return returnData
        });
}
export function checkSMSApi(data) {
    console.log('API getUserApi-->%j', GETOPTION());
    return fetch(`/api/checkCode?name=${data.name}&phone=${data.number}&code=${data.code}`, GETOPTION())
        .then((response) => { return response.json() }).then((res) => {
            let returnData = res
            console.log("returnData.status: ", returnData.status)
            if (returnData.status == 0)
                returnData.type = "CODE_OK"
            else
                returnData.type = "CODE_FAIL"

            return returnData
        });
}