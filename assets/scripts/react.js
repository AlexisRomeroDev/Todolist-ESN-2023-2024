
import ReactDOM from 'react-dom/client'
import React from 'react'

function MyApp() {
  return <p>React is working</p>
}

const container = document.getElementById('root');
const root = ReactDOM.createRoot(container);
root.render(<MyApp />);