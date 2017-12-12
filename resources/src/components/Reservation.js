// 負責渲染和清理資料

import CheckDetail from "./Reservation/CheckDetail";
import CheckService from "./Reservation/CheckService";
import CheckTime from "./Reservation/CheckTime";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    Button = ReactBootstrap.Button,
    Alert = ReactBootstrap.Alert;

class Reservation extends React.Component{
    constructor(props){
        super(props);

        this.state = {show: 0};
        this.changeView = this.changeView.bind(this);
        this.changeHandle = this.changeHandle.bind(this);
        this.next = this.next.bind(this);
        this.prev = this.prev.bind(this);
    }
    changeView(index){
        if(index === undefined || index >= this.state.show) return;
        this.changeHandle(index);
        this.setState({show: index});
    }
    next(){
        const index = this.state.show + 1;
        this.changeHandle(index);
        if(index <= 2) this.setState({show: index});
    }
    prev(){
        const index = this.state.show - 1;
        this.changeHandle(index);
        this.setState({show: index});
    }
    changeHandle(index){
        // 清理資料或呼叫API
        switch (index) {
            case 0:
                // if(this.prop.reservation != null) this.props.clearReservation();
                break;
            case 1:
                
                break;
            case 2:

                break;
            case 3:
            // 所有資料都填妥要送出
            default:
                break;
        }
    }
    render(){
        // steps
        const stepsData = ["選擇服務","選擇時間","確認細節"], pointer = {cursor: "pointer"}, currentStepStyle = {cursor:"pointer",color: "red"};
        let steps = stepsData.map((step, index,arr)=>{
                let divider = index < arr.length - 1 && <span> > </span>;
                return (
                <span>
                    <span 
                        onClick={()=>{ this.changeView(index); }}
                        style = {index <= this.state.show ? (this.state.show === index?currentStepStyle:pointer): null}
                    >{step}</span>
                    {divider}
                </span>);
              });

        // forms to show
        let el;
        switch(this.state.show){
            case 0:
                el = (
                    <CheckService next={()=>{this.changeView(1)}}/>);
                break;
            case 1:
                el = <CheckTime next={()=>{this.changeView(2)}}
                                prev={()=>{this.changeView(0)}}/>;
                break;
            case 2:
                el = <CheckDetail prev={()=>{this.changeView(1)}}/>;
                break;
        }

        return(
            <Grid>
                <Row className="show-grid">
                <Col md={12}>
                    <div style={{backgroundColor: "#F5F5F5",borderRadius:"16px",border:"solid 1px #E8E8E8", padding: "8px 16px"}}>
                        {steps}
                    </div>
                </Col>
                {this.state.show > 0 && <Col md={12} >
                    <span onClick={this.prev} style={pointer}>{"< 返回上一步"}</span>
                </Col>}
                    {el}    
                <Col md={12}>
                    <Button bsStyle="primary" bsSize="large" onClick={this.next}>
                        {this.state.show == 2?"送出":"下一步"}
                    </Button>
                </Col>
                </Row>
            </Grid>
        );
    }
}

module.exports = Reservation;