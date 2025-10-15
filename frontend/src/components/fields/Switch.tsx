import React from 'react';
import './Switch.css'

interface ToggleSwitchProps {
  id: string;
  label: string;
  checked: boolean |undefined ;
  onChange: (checked: any) => void;
  name?: string;
  handleDispatch?: ((name:string,value:string|boolean) => void) | undefined
}

const ToggleSwitch: React.FC<ToggleSwitchProps> = ({ id, label, checked, onChange, name, handleDispatch }) => {
  
  const handleChange: React.ChangeEventHandler<HTMLInputElement> = (e) => {
    const newChecked = e.target.checked;

    if (onChange) {
        onChange((prevFormData:any) => ({
            ...prevFormData,
            [e.target.name]: newChecked,
        }));      
    }    

    if(handleDispatch){
      handleDispatch(e.target.name,newChecked)
    }
    
  };

  //console.log(name,checked)

  return (
    <div className="flex items-center">
      <label htmlFor={id} className="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">{label}</label>
      <div className="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
        <input
          type="checkbox"
          id={id}
          name={name}
          checked={checked||false}
          onChange={handleChange}
          className="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
        />
        <label
          htmlFor={id}
          className="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
        ></label>
      </div>
    </div>
  );
};

export default ToggleSwitch;
