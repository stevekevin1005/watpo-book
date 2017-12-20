import reducers from "./reducers";
import { combineReducers } from "redux";

const rootReducer = combineReducers({
    lastAction: reducers.lastAction,
    loading: reducers.loading,
    lang: reducers.lang,
    reservation: reducers.reservation,
    sourceData: reducers.sourceData
  });

  module.exports = rootReducer;