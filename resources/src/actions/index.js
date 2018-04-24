
import { sendSMSApi, checkSMSApi } from '../api/laravelserver'
import phonedispatcher from '../dispatchers/phoneValidator'

export const sendSMS = (name, number) => {
    let requestData = {
        name,
        number
    }
    return dispatch => {
        sendSMSApi(requestData)
            .then((data) => phonedispatcher(data.type, data))
    };
}
export const checkSMS = (name, number, code) => {
    let requestData = {
        name,
        number,
        code
    }
    return dispatch => {
        checkSMSApi(requestData)
            .then((data) => phonedispatcher(data.type, data))
    };
}

export const SwitchCodeModal = (isopen) => ({ type: "HANDLE_MODAL", payload: { isopen } })