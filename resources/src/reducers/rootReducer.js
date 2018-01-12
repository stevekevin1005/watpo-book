import reducers from "./reducers";
import { combineReducers } from "redux";

const rootReducer = combineReducers({
    lastAction: reducers.lastAction,
    loading: reducers.loading,
    reservation: reducers.reservation,
    sourceData: reducers.sourceData,
    checkOrdersInfo: reducers.checkOrdersInfo
  });

  module.exports = rootReducer;