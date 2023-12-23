import iFrame from './components/iframe';
import Button from './components/button';
import ProgressBar from './components/progressBar';

export default (editor, config = {}) => {
    iFrame(editor, config);

    Button(editor, config);
    
    ProgressBar(editor, config);
}
