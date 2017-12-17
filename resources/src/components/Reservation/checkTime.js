// 負責寫資料(日期,時段)到global state

import Calendar from "./Calendar";
import {connect} from "react-redux";

const Grid = ReactBootstrap.Grid,
Row = ReactBootstrap.Row,
Col = ReactBootstrap.Col,
ListGroupItem = ReactBootstrap.ListGroupItem,
ListGroup = ReactBootstrap.ListGroup;

class CheckTime extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        return(
            <Grid>
            <Row className="show-grid">
            <Col md={5}>
                <Calendar />
            </Col>
            <Col md={5}>
                <div className="timePeriods">
                    <span className="timePeriod">19:00</span>
                    <span className="timePeriod">19:00</span>
                    <span className="timePeriod">19:00</span>
                    <span className="timePeriod">19:00</span>
                    <span className="timePeriod">19:00</span>
                    <span className="timePeriod">19:00</span>
                    <span className="timePeriod">19:00</span>
                    <span className="timePeriod">19:00</span>
                    <span className="timePeriod">19:00</span>
                </div>
            </Col>
            </Row>
            </Grid>
        );
    }
}

module.exports = CheckTime;