// 負責渲染和清理資料

import Nav from "./Nav";
import Landpage from "./Landpage";
import Reservation from "./Reservation";
import BookCheck from "./BookCheck";
import { Switch, Route, BrowserRouter } from "react-router-dom";
import i18n from '../i18n';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import clearReservation from "../dispatchers/clearReservation";

class App extends React.Component{
    constructor(props){
        super(props);       
    }
    render(){
        return(
                <BrowserRouter>
                    <div>
                        <Nav/>
                        <Switch>
                            <Route exact path="/" component={Landpage} />
                            <Route path="/reservation/:step" component={Reservation} />
                            <Route path="/book/check/:step" component={BookCheck} />
                        </Switch>
                    </div>
                </BrowserRouter>
        );
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        clearReservation: clearReservation
    },dispatch);
}
  
App = connect(null, mapDispatchToProps)(App);


module.exports = App;