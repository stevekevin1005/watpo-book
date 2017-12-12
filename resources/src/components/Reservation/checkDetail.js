// 負責寫資料(師傅,人數,房號,姓名,電話)到global state

const Button = ReactBootstrap.Button,
Grid = ReactBootstrap.Grid,
Row = ReactBootstrap.Row,
Col = ReactBootstrap.Col,
FormGroup = ReactBootstrap.FormGroup,
FormControl = ReactBootstrap.FormControl,
ControlLabel = ReactBootstrap.ControlLabel,
HelpBlock = ReactBootstrap.HelpBlock;

class CheckDetail extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        return(
            <Grid>
            <Row className="show-grid">
            <FormGroup controlId="formControlsSelect">
                <Col md={5}>
                        <ControlLabel>服務人員</ControlLabel>
                        <FormControl componentClass="select" placeholder="select">
                            <option value="select">select</option>
                            <option value="other">...</option>
                        </FormControl>
                        <ControlLabel>房號</ControlLabel>
                        <FormControl componentClass="select" placeholder="select">
                            <option value="select">select</option>
                            <option value="other">...</option>
                        </FormControl>
                        <ControlLabel>人數</ControlLabel>
                        <FormControl componentClass="select" placeholder="select">
                            <option value="select">select</option>
                            <option value="other">...</option>
                        </FormControl>
               </Col>
               
               <Col md={1}>
               <div className="divider"></div>
               </Col>

               <Col md={6}>
                    <ControlLabel>預約人姓名</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="Enter text"
                    />
                    <FormControl.Feedback />
                    <HelpBlock></HelpBlock>
                    <ControlLabel>連絡電話</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="Enter text"
                    />
                    <FormControl.Feedback />
                    <HelpBlock></HelpBlock>
                </Col>
             </FormGroup>
            </Row>
        </Grid>
        );
    }
}

module.exports = CheckDetail;