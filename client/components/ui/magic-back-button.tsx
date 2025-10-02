import * as React from 'react';
import { cn } from '@/lib/utils';
import { Button, ButtonProps } from '@/components/ui/button';
import { useRouter } from 'next/navigation';
import { usePageTrackerStore } from 'react-page-tracker';
import { ChevronLeft } from 'lucide-react';

export const MagicBackButton = React.forwardRef<
  HTMLButtonElement,
  ButtonProps & { backLink?: string }
>(({ className, onClick, children, backLink = '/', ...props }, ref) => {
  const router = useRouter();
  const isFirstPage = usePageTrackerStore((state) => state.isFirstPage);
  return (
    <Button
      className={cn('w-fit px-4 flex items-center', className)}
      variant="outline"
      size="icon"
      ref={ref}
      onClick={(e) => {
        if (isFirstPage) {
          router.push(backLink);
        } else {
          router.back();
        }
        onClick?.(e);
      }}
      {...props}
    >
      {children ?? <ChevronLeft />} Voltar
    </Button>
  );
});
MagicBackButton.displayName = 'MagicBackButton';
