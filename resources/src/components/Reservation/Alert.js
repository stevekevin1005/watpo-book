export default class Alert extends React.Component{
    render(){
        return ( <p style={{whiteSpace: "pre-line"}}>{this.props.text}<span style={{color: "red" }}>{this.props.notice?"("+this.props.notice+")":""}</span></p>);
    }
}