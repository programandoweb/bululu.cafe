import { NextPage } from 'next';

interface Props {
  embedHtml: string;
}

const GoogleMaps: NextPage<Props> = ({ embedHtml }) => {
  return (
    <div
      style={{ overflow: 'hidden', position: 'relative', height: '100%', width: '100%' }}
      dangerouslySetInnerHTML={{ __html: embedHtml }}
    />
  );
};

export default GoogleMaps;