import redux from "redux";

const reducers = {
  lastAction:
    (state = null, action) => {
      return action.type;
    },
  loading:
    (state = false, action) => {
      if (action.type == "TOGGLE_LOADING") {
        return action.payload;
      } else return state;
    },
  checkOrdersInfo:
    (state = {}, action) => {
      let result = JSON.parse(JSON.stringify(state));
      switch (action.type) {
        case "SET_CHECKORDERSINFO_NAME":
          result.name = action.payload;
          return result;
        case "SET_CHECKORDERSINFO_CONTACT_NUMBER":
          result.contactNumber = action.payload;
          return result;
        case "CLEAR_CHECKORDERSINFO_NAME":
          result.name = undefined;
          return result;
        case "CLEAR_CHECKORDERSINFO_CONTACT_NUMBER":
          result.contactNumber = undefined;
          return result;
        default:
          return state;
      }
    },
  phoneValidator:
    (state = {
      name: '',
      phone: '',
      reEnter: false,
      isopen: false
    }, action) => {
      let result = JSON.parse(JSON.stringify(state));
      let { name, phone, reEnter, isopen } = state;
      switch (action.type) {
        case "CODE_OK":
          return { name, phone, reEnter, isopen: false }
        case "HANDLE_MODAL":
          return { name, phone, reEnter, isopen: action.payload.isopen }
        case "SMS_IS_SENT":
          localStorage.setItem('phone', action.payload.phone);
          localStorage.setItem('name', action.payload.name);
          return {
            name: action.payload.name,
            phone: action.payload.phone,
            reEnter: false,
            isopen: true
          };
        case "RE_ENTER_CODE":
          return { name: state.name, phone: state.phone, reEnter: true, isopen: true }

        case "SMS_ERROR":
          result.name = undefined;
          result.reEnter = false;
          result.isopen = false;
          return result;
        default:
          return state;
      }
    }
};

module.exports = reducers;