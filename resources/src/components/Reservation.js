// 負責渲染和清理資料

import CheckDetail from "./Reservation/CheckDetail";
import CheckService from "./Reservation/CheckService";
import CheckTime from "./Reservation/CheckTime";
import { Link } from 'react-router-dom';

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    Button = ReactBootstrap.Button;

class Reservation extends React.Component{
    constructor(props){
        super(props);
    }
    componentDidReceiveProps(nextProps){
        // 清資料或發請求
        switch(nextProps){

        }
    }
    render(){
        // steps
        const currentStep = parseInt(this.props.match.params.step),
              stepsData = ["選擇服務","選擇時間","確認細節"], pointer = {cursor: "pointer"}, currentStepStyle = {cursor:"pointer",color: "red"};
        let steps = stepsData.map((step, index,arr)=>{
                let divider = index < arr.length - 1 && <span> > </span>;
                if(currentStep > index)return (
                    <span key={index}>
                        <Link to={"/reservation/"+index}>
                            <span 
                                style = {currentStep === index?currentStepStyle: null}
                            >{step}</span>
                        </Link>
                    {divider}</span>);
                return (
                    <span>
                            <span 
                                style = {currentStep === index?currentStepStyle: null}
                            >{step}</span>
                    {divider}</span>);
              });

        // forms to show
        let el;
        switch(currentStep){
            case 0:
                el = (
                    <CheckService/>);
                break;
            case 1:
                el = <CheckTime/>;
                break;
            case 2:
                el = <CheckDetail/>;
                break;
        }

        return(
            <Grid>
            <div className="reservationContainer">
                <Row className="show-grid">
                <Col md={12}>
                    <div style={{backgroundColor: "#F5F5F5",borderRadius:"16px",border:"solid 1px #E8E8E8", padding: "8px 16px"}}>
                        {steps}
                    </div>
                </Col>
                {currentStep > 0 && <Col md={12} >
                    <Link to={"/reservation/"+ (currentStep - 1)}><span style={pointer}>{"< 返回上一步"}</span></Link>
                </Col>}
                    {el}    
                <Col md={12}>
                    <Link to = {"/reservation/"+(currentStep + 1)}>
                    <Button bsStyle="primary" bsSize="large" onClick={this.next}>
                        {currentStep == 2?"送出":"下一步"}
                    </Button>
                    </Link>
                </Col>
                </Row>                
                </div>
            </Grid>
            
        );
    }
}

module.exports = Reservation;