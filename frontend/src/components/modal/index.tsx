'use client'

import React, { useEffect } from 'react';
import ReactModal from 'react-modal';
import { useSelector, useDispatch } from 'react-redux';
import {
  setShowModal,
  setOpenSC,
  setAcceptModal,
  setDialogMessageConfirm
} from '@/store/Slices/dialogMessagesSlice';
import ThemeProvider from '@/providers/ThemeProvider';
import Logo from '@/components/logo';
import useFormDataNew from "@/hooks/useFormDataNew";
import './index.css';

const customStyles = {
  content: {
    outline: 'none',
    border: 'none',
    padding: 0,
    inset: 'unset'
  },
};

const ListArray: React.FC<{ data: any[] }> = ({ data }) => (
  <div className="text-sm sm:text-base space-y-1">
    {data.map((item, index) => (
      <div key={index}>{item}</div>
    ))}
  </div>
);

const ListObject: React.FC<{ data: Record<string, any> }> = ({ data }) => {
  if (!data || Object.keys(data).length === 0) return null;
  return (
    <ul className="text-sm sm:text-base text-left space-y-1">
      {Object.entries(data).map(([key, value]) => (
        <li key={key}>
          <span className="font-semibold">{key}: </span>
          <span>{String(value)}</span>
        </li>
      ))}
    </ul>
  );
};

const Modal: React.FC = () => {
  const dispatch = useDispatch();
  const { open, message, list, accept, openSC, size, title, confirm } = useSelector((s: any) => s.dialog) || {};
  const { handleRequest, backend, search } = useFormDataNew(false, false, false);

  useEffect(() => {
    const element = document.getElementById('__next');
    if (element) ReactModal.setAppElement(element);
  }, []);

  const handleCloseModal = () => {
    dispatch(setShowModal(false));
    dispatch(setOpenSC(null));
  };

  const handleConfirm = () => {
    dispatch(setShowModal(false));
    dispatch(setDialogMessageConfirm(confirm.action));
  };

  const handleDelete = () => {
    handleCloseModal();
    dispatch(setAcceptModal(null));
    handleRequest(`${backend}${document.location.pathname}/${accept.id}${search}`, "delete").then(() => {
      handleCloseModal();
    });
  };

  const className = `container-modal ${typeof size === 'string' ? size : ''}`;

  return (
    <ThemeProvider>
      <ReactModal
        isOpen={!!open}
        onRequestClose={handleCloseModal}
        shouldCloseOnOverlayClick={true}
        style={customStyles}
        overlayClassName="fixed inset-0 bg-black/70 flex items-center justify-center p-3 z-[9999]"
        className="w-full sm:w-[90%] md:w-[600px] lg:w-[700px] xl:w-[800px] max-h-[90vh] overflow-y-auto bg-white dark:bg-gray-900 rounded-2xl shadow-2xl outline-none"
      >
        <div className="p-4 sm:p-6 md:p-8 flex flex-col h-full justify-between text-center text-gray-800 dark:text-gray-100">
          {/* Header */}
          <div>
            <div className="flex justify-center items-center mb-4">
              <Logo />
            </div>

            {title && (
              <div
                className="mb-3 text-lg font-bold"
                dangerouslySetInnerHTML={{ __html: title }}
              />
            )}

            {confirm && (
              <div
                className="mb-3 text-base"
                dangerouslySetInnerHTML={{ __html: confirm.label }}
              />
            )}

            {message && (
              <div
                className="mb-3 text-sm sm:text-base"
                dangerouslySetInnerHTML={{
                  __html: message?.message || message
                }}
              />
            )}

            {list && Array.isArray(list) && <ListArray data={list} />}
            {list && typeof list === 'object' && <ListObject data={list} />}
          </div>

          {/* Footer / Buttons */}
          <div className="mt-6 flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
            {accept && (
              <button
                type="button"
                className="w-full sm:w-auto rounded-xl bg-brand-500 py-2 px-4 text-sm sm:text-base font-medium text-white transition duration-200 hover:bg-brand-600 active:bg-brand-700"
                onClick={handleDelete}
              >
                Aceptar
              </button>
            )}

            {confirm && (
              <button
                type="button"
                className="w-full sm:w-auto rounded-xl bg-brand-500 py-2 px-4 text-sm sm:text-base font-medium text-white transition duration-200 hover:bg-brand-600 active:bg-brand-700"
                onClick={handleConfirm}
              >
                Sí, estoy seguro
              </button>
            )}

            <button
              type="button"
              className="w-full sm:w-auto rounded-xl bg-gray-500 py-2 px-4 text-sm sm:text-base font-medium text-white transition duration-200 hover:bg-gray-600"
              onClick={handleCloseModal}
            >
              Cerrar
            </button>
          </div>
        </div>
      </ReactModal>
    </ThemeProvider>
  );
};

export default Modal;
