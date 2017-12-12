import {createStore, applyMiddleware} from "redux";
import thunk from "redux-thunk"
import rootReducer from "./reducers/rootReducer";

const store = createStore(rootReducer,applyMiddleware(thunk));

store.subscribe(()=>{
  const action = store.getState().lastAction;
  debug(action); 
});


module.exports = store;