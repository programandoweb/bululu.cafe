import React from 'react';

interface TextLogoProps {
    name: string;
    textColor1?: string;
    textColor2?: string;
}

const TextLogo: React.FC<TextLogoProps> = ({ name, textColor1, textColor2 }) => {
    return (
        <div className="flex items-center">
            <div className="font-poppins text-[26px] font-bold uppercase text-navy-700 dark:text-white">
                <span className={ textColor1}>
                    {process.env.NEXT_PUBLIC_NAME}
                </span>
                <span
                    className={"ml-2 mt-2 font-medium dancing-script " + textColor2}                    
                >
                    {process.env.NEXT_PUBLIC_NAME2 ||name}
                </span>
            </div>
        </div>
    );
}

export default TextLogo;
