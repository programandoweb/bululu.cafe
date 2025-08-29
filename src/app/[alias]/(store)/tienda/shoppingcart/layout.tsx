import Link from "next/link";
import { MdArrowBackIos } from "react-icons/md";

export default function RootLayout({ children, params }: { children: React.ReactNode, params: { alias: string } }) {
  const url =   "/"+params.alias+"/tienda"
  return (
    <>
      <div className="mt-4">
        <Link href={url} className="flex items-center">
          <MdArrowBackIos className="h-6 w-6 mr-2" /> Volver a la tienda
        </Link>
      </div>
      {children}
    </>
  );
}
