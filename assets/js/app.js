import React from 'react';
import ReactDOM from 'react-dom';

import Items from './Components/Items';

import b1 from './Components/b1';


class App extends React.Component {
 constructor() {
     super();

     this.state = {
         entries: []
     };
 }

 componentDidMount() {
     fetch('https://jsonplaceholder.typicode.com/posts/')
         .then(response => response.json())
         .then(entries => {
             this.setState({
                 entries
             });
         });
 }

 render() {
console.log('reeeeee');
     return (
         <div className="row">
             {this.state.entries.map(
                 ({ id, title, body }) => (
                     <b1
                         key={id}
                         title={title}
                         body={body}
                     >
                     </b1>
                 )
             )}
         </div>
     );
 }
}

ReactDOM.render(<App />, document.getElementById('root'));
